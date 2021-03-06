<?php

namespace App\Lib;

class XmlParser {

    public static function JsonToXml($json)
    {
        $striped = str_replace('"', '', $json);
        $split = explode(':{', $striped);
        $amount = count($split);
        $xml = '';
        $xml .= "<root>";

        for ($i = 0; $i < $amount; $i++) {
            $split[$i] = str_replace('{', '', $split[$i]);
            $split[$i] = str_replace('}', '', $split[$i]);
            $split[$i] = explode(',', $split[$i]);
        }

        $saves = [];
        for ($i = $amount - 1; $i >= 0; $i--) {
            $temp = $split[$i];

            foreach ($temp as $value) {
                $ar = explode(':', $value);

                if (isset($ar[1])) {
                    $xml .= '<'.$ar[0].'>';
                    $xml .= $ar[1];
                    $xml .= '</'.$ar[0].'>';
                }else{
                    array_push($saves, $ar[0]);
                }
            }
        }
        foreach ($saves as $tag) {
            $xml = '<'.$tag.'>'.$xml.'</'.$tag.'>';
        }

        $xml .= "</root>";
        return $xml;
    }

}
