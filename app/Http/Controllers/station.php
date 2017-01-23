<?php
require_once 'opencsv.php';
class station extends csv {

    public static function getStations()
    {
        return self::getCsvContent('stations.csv');
    }

    public static function getStationsWhere($variable, $value)
    {
        $original = self::getStations();
        return self::getWhere($original, $variable, $value);
    }

    public static function getStationsFieldsWhere($variable, $value, $fields)
    {
        $data = self::getStationsWhere($variable, $value);
        $result = self::getFields($data, $fields);
        return $result;
    }
}
print_r(station::getStationsFieldsWhere('Country', 'Japan', ['id', 'Station name', 'Country', 'Longitude']));