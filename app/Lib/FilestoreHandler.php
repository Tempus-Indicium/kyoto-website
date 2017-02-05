<?php

namespace App\Lib;

use \App\Station;
use Carbon\Carbon;
use App\Lib\FilestoreHandler;

class FilestoreHandler
{
    // @NOTE: bytes are in big endian order! Machine order did not work local for me!
    // unsigned short (2 bytes) in BE: n
    // signed short (2 bytes) in BE: n ; but perform * -1 after


    public function __construct() {

    }

    public static function getTopTenAsiaForDate($carbonDate) {
        $fileName = "top-ten-asia-".$carbonDate->year."-".$carbonDate->month."-".$carbonDate->day.".json";
        $fullFilePath = env('FILESTORE_LOCATION').'/'.$fileName;
        if (!file_exists($fullFilePath)) {
            return false;
        }

        $jsonString = file_get_contents($fullFilePath);
        return json_decode($jsonString, true);
    }

    public static function getLastFiveSecondsForStation($station, $fullFilePath) {
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
        $result['temperature'] = FilestoreHandler::calculateAverage(
            $measurements[$result['stationId']]['temperature'],
            $measurementsCounter
        );
        $result['dewp'] = FilestoreHandler::calculateAverage(
            $measurements[$result['stationId']]['dewp'],
            $measurementsCounter
        );
        $result['visibility'] = FilestoreHandler::calculateAverage(
            $measurements[$result['stationId']]['visibility'],
            $measurementsCounter
        );

        return $result;
    }

    public static function getCurrentFileName() {
        $carbon = Carbon::now('Europe/Amsterdam');
        $potentialFile = $carbon->format(env('FILESTORE_FORMAT'));
        // $potentialFile = "2017-02-04-16"; //@NOTE: debug!
        return env('FILESTORE_LOCATION').'/'.$potentialFile;
    }


    public static function calculateAverage($sum, $count, $round = 2) {
        return round($sum / $count, 2);
    }

    public static function getUnsignedShort($byteArray) {
        return unpack('n', $byteArray)[1];
    }

    // using example idea from: http://stackoverflow.com/questions/16124059/trying-to-read-a-twos-complement-16bit-into-a-signed-decimal
    // converts 16bit binary number string to integer using two's complement
    public static function getSignedShort($byteArray) {
        $uShort = unpack('n', $byteArray)[1];
        if (0x8000 & $uShort) {
            $uShort = - (0x010000 - $uShort);
        }
        return $uShort;
    }

}
