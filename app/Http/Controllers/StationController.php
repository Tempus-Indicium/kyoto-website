<?php

namespace App\Http\Controllers;

use \App\Measurement;
use Illuminate\Http\Request;
use App\Lib\FilestoreHandler;
use Carbon\Carbon;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //    $stations = Measurement::GetTopTen("2017-01-14");
        $carbonDate = Carbon::yesterday('Europe/Amsterdam');
        $stations = FilestoreHandler::getTopTenAsiaForDate($carbonDate);

        $maxDaysBack = 10; // safety variable to prevent infinite loop
        while (!$stations) {
            $carbonDate->subDay();
            $stations = FilestoreHandler::getTopTenAsiaForDate($carbonDate);

            $maxDaysBack--;
            if ($maxDaysBack === 0)
                break;
        }

        dd($stations); //average average
        return view("stations.index", ["stations" => $stations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
