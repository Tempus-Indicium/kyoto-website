<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getLastHumidity($stn, $amount)
    {
        $query = '
        SELECT * from unwdmi.measurements
        where stn = 475810
        order by id desc
        limit '.$amount.';';
        $data = DB::select($query);
        $humidity_array = [];
        foreach ($data as $row)
        {
            $humidity = $this->calculateHumidity($row->temperature, $row->dew);
            array_push($humidity_array, $humidity);
        }
        return $humidity_array;
    }

    public function getHumidity($stn)
    {
        $query = "SELECT temperature, dew FROM measurements where acquisition_date = '2017-01-14' and acquisition_time IN ('15:33:12','15:33:15') and stn = ".$stn;
        $data = DB::select($query);
        return $data;
    }

    public function page($stn)
    {
        //$data = $this->getLastNumberWithTimeDifference(30, 5, $stn);
        //$data = $this->getHumidity($stn);
        $data = $this->getLastHumidity($stn, 30);
        $xas = $this->arrayConvert($this->XasValues(600));
        return view('station_information', ['time'=>$xas, 'data' => $this->arrayConvert($data)]);
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
        $array = [];
        for ($i = 0; $i <= $end; $i += 5){
            array_push($array, $i);
        }
        return $array;
    }

}