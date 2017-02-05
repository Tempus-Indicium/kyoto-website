<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lib\FilestoreHandler;
use App\Station;

class station_information extends Controller
{

    private function calculateHumidity($T, $Td)
    {
        $RH = 100*exp((17.625*$Td)/(243.04+$Td))/exp((17.625*$T)/(243.04+$T));
        return round($RH, 1);
    }

    private function getLastNumberWithTimeDifference($amount, $difference, $stn)
    {
        $query = "SELECT temperature, dew FROM measurements where acquisition_date = '2017-01-14' and stn = ".$stn." and acquisition_time IN (";
        $time = time();
        for ($i=0; $i<$amount; $i++){
            $back = $time - $difference * $i;
            $query .= '"'.date('H:i:s' ,$back).'"';
            if ($amount - 1 == $i) {
                $query .= ');';
            }else{
                $query .= ',';
            }
        }

        $data = DB::select($query);
        return $data;
    }

    public function getLastHumidity($station, $amount)
    {
        // $query = '
        // SELECT * from measurements
        // where stn = '.$stn.'
        // order by id desc
        // limit '.$amount.';';
        // $data = DB::select($query);
        $humidity_array = [];

        $fullFilePath = FilestoreHandler::getCurrentFileName();

        // get a couple samples
        $measurementsData = [];
        for ($i=0; $i < $amount; $i++) {
            $measurementsData[] = FilestoreHandler::getLastFiveSecondsForStation($station, $fullFilePath);
        }

        foreach ($measurementsData as $row)
        {
            $humidity = $this->calculateHumidity($row['temperature'], $row['dewp']);
            array_push($humidity_array, $humidity);
        }
        return $humidity_array;
    }

    public function getHumidity($stn)
    {
        // $query = "SELECT temperature, dew FROM measurements where acquisition_date = '2017-01-14' and acquisition_time IN ('15:33:12','15:33:15') and stn = ".$stn;
        // $data = DB::select($query);
        return $data; // throws error, @debugging
    }

    public function page($stn)
    {
        $station = Station::where(['id' => $stn])->first();

        $data = $this->getLastHumidity($station, 5);
        $xas = $this->arrayConvert($this->XasValues(600));
        return view('station_information', ['xas'=>$xas, 'data' => $this->arrayConvert($data), 'stn' => $station]);
    }

    public function arrayConvert($array)
    {
        $new_array = '[';
        foreach ($array as $item){
            $new_array .= $item;
            $new_array .= ',';
        }
        $new_array = rtrim($new_array, ",");
        $new_array .= ']';
        return $new_array;
    }

    public function XasValues($end)
    {
        $counter = 0;
        $array = [];
        for ($i = 0; $i <= $end; $i += 5){
            if ($i % 60 == 0){
                array_push($array, $counter);
                $counter++;
            }else {
                array_push($array, ' ');
            }
        }
        return $array;
    }

    public function tableInfo($station)
    {
        // $query = '
        // SELECT temperature, dew, visibility from measurements
        // where stn = '.$stn.'
        // order by id desc
        // limit 1;';
        // $info = DB::select($query);
        $fullFilePath = FilestoreHandler::getCurrentFileName();
        $info = [];
        $info[] = FilestoreHandler::getLastFiveSecondsForStation($station, $fullFilePath);
        $Json = '';
        foreach ($info as $row) {
            $Json .= '"temperature":' . $row['temperature'] . ', ';
            $Json .= '"humidity":' . $this->calculateHumidity($row['temperature'], $row['dewp']) . ', ';
            $Json .= '"visibility":' . $row['visibility'];
        }
        return $info;
    }

    public function ajax($stn)
    {
        $station = Station::where(['id' => $stn])->first();
        $data = $this->getLastHumidity($station, 1);
        $table = $this->tableInfo($station);
        //return view('ajaxJson', ['data' => $this->arrayConvert($data)]);
        return response()->json([
        'data' => $data,
            'temperature' => $table[0]['temperature'],
            'visibility' => $table[0]['visibility']

    ]);
    }
}
