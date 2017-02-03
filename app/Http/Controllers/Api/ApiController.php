<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\FileStoreHandler;
use App\Station;

class ApiController extends Controller
{

    public function lastFiveSeconds($stationId) {
        $fileName = FilestoreHandler::getCurrentFileName();
        if (!file_exists($fileName)) {
            return response()
            ->json([
                'status' => 'error',
                'error' => 'Corresponding measurements file could not be found: '.$fileName,
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

        $data = FilestoreHandler::getDataStringFromEOF($fileName, (15600*5));

        $measurements = [];
        // loop back, 5 seconds at a time
        for ($i = strlen($data); $i > 0 ; $i -= (15600*5) ) {
            // start measure
            $rowCounter = 1;
            for ($c = 10; $c < (15600*5); $c += env('FILESTORE_ROWBYTES')) {
                $stationId = FilestoreHandler::getUnsignedShort(substr($data, ($i-$c) , 2));
                if ($stationId != $station->id)
                    continue; // skip everything that is not from this station

                if (!array_key_exists($stationId, $measurements)) {
                    $measurements[$stationId] = [];
                    $measurements[$stationId]['country'] = $station->country;
                    $measurements[$stationId]['acqDate'] = FilestoreHandler::getUnsignedShort(substr($data, ($i-$c)+2 , 2)); // @TODO: fix timestamp to date

                    $measurements[$stationId]['temperature'] = 0;
                    $measurements[$stationId]['dewp'] = 0;
                    $measurements[$stationId]['visibility'] = 0;
                }

                $temperature = FilestoreHandler::getSignedShort(substr($data, ($i-$c)+4 , 2));
                // @TODO: test if variable needs correction..
                $measurements[$stationId]['temperature'] += ($temperature / 10);

                $dew = FilestoreHandler::getSignedShort(substr($data, ($i-$c)+6 , 2));
                $measurements[$stationId]['dewp'] += ($dew / 10);

                $visibility = FilestoreHandler::getUnsignedShort(substr($data, ($i-$c)+8 , 2));
                $measurements[$stationId]['visibility'] += ($visibility / 10);

                $rowCounter++;
            }
            if ($rowCounter > 1) {
                foreach ($measurements as $stnId => $measurement) {
                    $measurements[$stnId]['temperature'] = round($measurements[$stnId]['temperature'] / $rowCounter, 2);
                    $measurements[$stnId]['dewp'] = round($measurements[$stnId]['dewp'] / $rowCounter, 2);
                    $measurements[$stnId]['visibility'] = round($measurements[$stnId]['visibility'] / $rowCounter, 2);
                }
            }
        }

        // @TODO: return xml when needed
        return response()
        ->json(['measurementsOver1Second' => $measurements]);
    }





}
