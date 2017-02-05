<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\FilestoreHandler;
use App\Station;
use App\User;
use Carbon\Carbon;

class ApiController extends Controller
{

    public function topTenAsia(Request $request, $dateString) {
        $user = User::getUserFromRequest($request);
        if (!$user) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'User could not be authenticated properly',
            ]);
        }
        try {
            $carbonDate = Carbon::createFromFormat(env('API_DATEFORMAT', 'Y-m-d'), $dateString);
        } catch (\Exception $e) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'Date could not be parsed properly: '.$e->getMessage(),
            ]);
        }

        // top-ten-asia-2017-2-4
        $resultData = FilestoreHandler::getTopTenAsiaForDate($carbonDate);
        if (!$resultData) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'Corresponding Top-Ten data does not exist. Perhaps it was not generated yet.',
            ]);
        }

        return response()
        ->json([
            'status' => 'success',
            'data' => $resultData,
        ]);
    }

    public function lastFiveSeconds(Request $request, $stationId) {
        $user = User::getUserFromRequest($request);
        if (!$user) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'User could not be authenticated properly',
            ]);
        }

        $fullFilePath = FilestoreHandler::getCurrentFileName();
        if (!file_exists($fullFilePath)) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'Corresponding measurements file could not be found: '.$fullFilePath,
            ]);
        }

        $station = Station::select('id', 'country')->where(['id' => $stationId])->first();
        if (!$station) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'Station could not be found',
            ]);
        }


        $measurements = []; $measurementsCounter = 0;
        // read a file of measurements
        $fileResource = fopen($fullFilePath, 'rb');
        $fileSize = filesize($fullFilePath);
        // $data = FilestoreHandler::getDataStringFromEOF($fileName, (15600*5));
        $seekOffset = filesize($fullFilePath) - (15600 * 5);
        fseek($fileResource, $seekOffset, SEEK_SET); // set the position at EOF-5sec

        $secondMarkerFound = false;
        // while position of the pointer is lower than the total filesize - 1 row
        while (ftell($fileResource) < $fileSize - env('FILESTORE_ROWBYTES')) {
            // first find the secondMarker bytes
            if (!$secondMarkerFound) {
                $data = fread($fileResource, 2);
                $potentialMark = FilestoreHandler::getUnsignedShort(substr($data, 0 , 2));

                if ($potentialMark == 65535) // 0xFFFF
                    $secondMarkerFound = true;

                continue;
            }
            // then read a measurement at a time (10 bytes)
            $data = fread($fileResource, env('FILESTORE_ROWBYTES'));

            $stationId = FilestoreHandler::getUnsignedShort(substr($data, 0 , 2));

            // check if we didnt hit a secondMarker
            if ($stationId == 65535) {
                // go backwards 8 bytes to restart just after the secondMarker
                fseek($fileResource, -(env('FILESTORE_ROWBYTES')-2), SEEK_CUR);
                continue;
            }

            if ($stationId != $station->id)
                continue; // skip everything that is not from this station

            // start off the measurements reading, we found our station
            if (!array_key_exists($stationId, $measurements)) {
                $station = Station::select('id', 'country', 'name')->where(['id' => $stationId])->first();
                if (!$station) {
                    return response()
                    ->json([
                        'status' => 'error',
                        'error' => "Invalid station number: corrupt measurements data. Please contact Tempus-Indicium",
                    ]);
                }

                $measurements[$stationId] = [];
                $measurements[$stationId]['country'] = $station->country;
                // $measurements[$stationId]['acqDate'] = "This feature is not implemented yet.";
                // $measurements[$stationId]['acqDate'] = FilestoreHandler::getUnsignedShort(substr($data, 2 , 2)); // @TODO: fix timestamp to date

                $measurements[$stationId]['temperature'] = 0;
                $measurements[$stationId]['dewp'] = 0;
                $measurements[$stationId]['visibility'] = 0;
            }

            // add temperature, dewp, visibility measurements to the pool
            $temperature = FilestoreHandler::getSignedShort(substr($data, 4 , 2));
            // @TODO: test if variable needs correction..
            $measurements[$stationId]['temperature'] += ($temperature / 10);

            $dew = FilestoreHandler::getSignedShort(substr($data, 6 , 2));
            $measurements[$stationId]['dewp'] += ($dew / 10);

            $visibility = FilestoreHandler::getUnsignedShort(substr($data, 8 , 2));
            $measurements[$stationId]['visibility'] += ($visibility / 10);

            // keep track of the number of measurements so we can take the average afterwards
            $measurementsCounter++;
        }

        if (empty($measurements)) {
            return response()
            ->json([
                'status' => 'error',
                'error' => "There were no recorded measurements found for this station in the last 5 seconds",
            ]);
        }

        // flatten and format the results
        $result = [];
        $result['stationId'] = array_keys($measurements)[0];
        $result['country'] = $measurements[$result['stationId']]['country'];
        $result['temperature'] = $this->calculateAverage(
            $measurements[$result['stationId']]['temperature'],
            $measurementsCounter
        );
        $result['dewp'] = $this->calculateAverage(
            $measurements[$result['stationId']]['dewp'],
            $measurementsCounter
        );
        $result['visibility'] = $this->calculateAverage(
            $measurements[$result['stationId']]['visibility'],
            $measurementsCounter
        );

        // @TODO: return xml when needed
        return response()
        ->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    private function calculateAverage($sum, $count, $round = 2) {
        return round($sum / $count, 2);
    }





}
