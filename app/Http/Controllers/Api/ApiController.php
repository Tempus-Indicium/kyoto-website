<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\FileStoreHandler;
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

        $result = FilestoreHandler::getLastFiveSecondsForStation($station, $fullFilePath);

        // @TODO: return xml when needed
        return response()
        ->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

}
