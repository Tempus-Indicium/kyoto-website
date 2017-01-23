<?php
class csv {

    public static function getCsvContent($filename)
    {
        $file = fopen($filename, 'r');

        $content = [];
        while(!feof($file))
        {
            $row = fgetcsv($file, 500, ',');
            array_push($content, $row);
        }
        fclose($file);
        return $content;
    }

    public static function getWhere($data, $variable, $value)
    {
        $filtered = [$data[0]];
        $key = array_search($variable, $data[0]);
        foreach ($data as $row)
        {
            if ($row[$key] == $value)
            {
                array_push($filtered, $row);
            }
        }
        return $filtered;
    }

    public static function getFields($data, $fields)
    {
        $keys = [];
        $filtered = [];

        foreach ($fields as $field)
        {
            $key = array_search($field, $data[0]);
            if (isset($key))
            {
                array_push($keys, $key);
            }
        }
        foreach ($data as $row)
        {
            $new_row = [];
            foreach ($keys as $key)
            {
                $value = $row[$key];
                array_push($new_row, $value);
            }
            array_push($filtered, $new_row);
        }

        return $filtered;
    }
}