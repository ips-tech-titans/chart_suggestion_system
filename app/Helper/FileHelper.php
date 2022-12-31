<?php

namespace App\Helper;

class FileHelper
{

    public function save($file)
    {

        $path = public_path('csv/') . time() . rand() . ".csv";
        move_uploaded_file($file, $path);

        return $path;
    }


    function getColumns($file, $delimiter = ',')
    {

        if (($handle = fopen($file, "r")) === false) {
            die("can't open the file.");
        }

        $csv_headers = fgetcsv($handle, 4000, $delimiter);

        $trimmed = $this->processHeader($csv_headers);

        fclose($handle);
        return $trimmed;
    }

    function csvtojson($file, $delimiter = ',')
    {

        if (($handle = fopen($file, "r")) === false) {
            die("can't open the file.");
        }

        $csv_headers = fgetcsv($handle, 4000, $delimiter);
        $trimmed_headers = $this->processHeader($csv_headers);

        $csv_json = array();

        while ($row = fgetcsv($handle, 4000, $delimiter)) {
            try {
                $csv_json[] = array_combine($trimmed_headers, $row);
            } catch (\Throwable $th) {
            }
        }

        fclose($handle);
        return $csv_json;
    }

    public function processHeader($csv_headers)
    {
        $trimmed = [];
        foreach ($csv_headers as $ch) {
            $trimmed_key = $this->createColumnKey($ch);
            $trimmed[$trimmed_key] = $ch;
        }

        return $trimmed;
    }

    public function createColumnKey($string)
    {
        return strtolower(str_replace(" ", "", $string));
    }

    public function isValidDate($date)
    {
        return (bool)strtotime($date);

        $timestamp = strtotime($date);
        return $timestamp ? $date : null;
    }
}
