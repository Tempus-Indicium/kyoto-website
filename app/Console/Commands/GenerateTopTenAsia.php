<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Station;
use App\Lib\FilestoreHandler;

class GenerateTopTenAsia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:top-ten-asia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a json file with the top ten asian weatherstations with the highest visibility of the past day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // retrieve the files based on date
        $carbon = Carbon::now('Europe/Amsterdam');
        // we want to focus on yesterday
        $carbon->subDay();

        // gather all files for the date
        $filePath = env('FILESTORE_LOCATION');
        $arrFilenames = array_diff(scandir($filePath, SCANDIR_SORT_ASCENDING), ['..', '.']);
        foreach ($arrFilenames as $key => $value) {
            $fileDateArr = explode("-", $value); // Y-m-d-H
            if ($carbon->month == intval($fileDateArr[1]) && $carbon->day != intval($fileDateArr[2])) {
                unset($arrFilenames[$key]);
            }
        }

        // abort when no files are found
        if (!$arrFilenames) {
            $this->info("Could not find files to calculate top ten Asia. Aborting.");
            return;
        }

        // use a progress bar provided by laravel for debugging the advancement of this command
        $bar = $this->output->createProgressBar(count($arrFilenames));

        // gather all the asian stations data for future usage
        // $stations = Station::select('id', 'country', 'name')->get();

        $candidates = []; $measurementCounters = [];
        // dd($arrFilenames);
        foreach ($arrFilenames as $key => $fileName) {
            // read a file of measurements
            $fileResource = fopen($filePath.'/'.$fileName, 'rb');
            $fileSize = filesize($filePath.'/'.$fileName);
            $this->info("Processing file: ".$fileName);

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

                // check if we need to register a new station in our averages array
                // and fill in initial values
                if (!array_key_exists($stationId, $candidates)) {
                    $station = Station::select('id')->where(['id' => $stationId])->first();
                    if (!$station) {
                        $this->error("ERROR: Station could not be found in database.");
                        dd($stationId);
                    }
                    $this->info("Adding StationId: ".$stationId);
                    $candidates[$stationId] = 0;
                    $measurementCounters[$stationId] = 0;
                }

                // add a visibility measurement to the pool
                $visibility = FilestoreHandler::getUnsignedShort(substr($data, 8 , 2));
                $candidates[$stationId] += ($visibility / 10);

                // keep track of the number of measurements so we can take the average afterwards
                $measurementCounters[$stationId]++;
            }

            $bar->advance();
            fclose($fileResource);
        }

        // average the visibility measurements
        foreach ($candidates as $stationId => $arrInfo) {
            $candidates[$stationId] = round(
                $candidates[$stationId] /= $measurementCounters[$stationId],
                2
            );
        }
        $bar->advance();

        // sort the array by value (descending)
        arsort($candidates, SORT_NUMERIC);

        // create new array with full information for all the winners
        $winners = []; $i = 0;
        foreach (array_slice($candidates, 0, 10, true) as $stationId => $avgVisibility) {
            $winners[$i] = [];
            $winners[$i]['stationId'] = $stationId;
            $winners[$i]['averageVisibility'] = $avgVisibility;
            $station = Station::select('country', 'name')->where(['id' => $stationId])->first();
            $winners[$i]['country'] = $station->country;
            $winners[$i]['name'] = $station->name;
            $i++;
        }

        // finally write the new json file. Using the same $carbon as before.
        $fileName = "top-ten-asia-".$carbon->year."-".$carbon->month."-".$carbon->day.".json";
        $result = file_put_contents(env('FILESTORE_LOCATION')."/".$fileName, json_encode($winners));

        if ($result) {
            echo "\n";
            $this->info("Succesfully wrote new top ten json file: ".$fileName);
            echo "\n";
        }

        echo "\n";
        $bar->finish();
        echo "\n";
    }
}
