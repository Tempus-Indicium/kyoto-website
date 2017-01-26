<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $table = 'stations_asia';
    public static function GetTopTen($date){
        return DB::select("select stations_asia.country as country, AVG(measurements.visibility) as average
                   from measurements
                   inner join stations_asia
                   where stations_asia.stn = measurements.stn and acquisition_date = \"".$date."\"
                   group by stations_asia.country
                   order by average desc limit 10;");
    }
}
