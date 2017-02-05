<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class map extends Controller
{
    public function stationsJapan()
    {
        $stations = DB::select("SELECT id, stn, name, latitude, longitude FROM stations_asia where country='JAPAN';");
        $markers = '';
        foreach ($stations as $station)
        {
            $markers .= "new google.maps.Marker({
                position: {lat: ".$station->latitude.", lng: ".$station->longitude."},
                map: map,
                title: '".$station->name."'
            }).addListener('click', function() {
                window.location = 'station_information/".$station->id."';
            });
            ";
        }
        return view('map_japan', ['markers' => $markers]);
    }
}
