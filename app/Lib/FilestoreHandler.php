<?php

namespace App\Lib;

use \App\Station;
use Carbon\Carbon;

class FilestoreHandler
{
    // @NOTE: bytes are in big endian order! Machine order did not work local for me!
    // unsigned short (2 bytes) in BE: n
    // signed short (2 bytes) in BE: n ; but perform * -1 after


    public function __construct() {

    }

    public static function getCurrentFileName() {
        $carbon = Carbon::now('Europe/Amsterdam');
        $potentialFile = $carbon->format(env('FILESTORE_FORMAT'));
        // $potentialFile = "2017-02-04-16"; //@NOTE: debug!
        return env('FILESTORE_LOCATION').'/'.$potentialFile;
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
