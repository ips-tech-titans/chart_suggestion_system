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


    function getColumns($file, $delimiter = ','){

        if (($handle = fopen($file, "r")) === false) {
            die("can't open the file.");
        }

       return $csv_headers = fgetcsv($handle, 4000, $delimiter);

    }

    function csvtojson($file, $delimiter = ',')
    {
        if (($handle = fopen($file, "r")) === false) {
            die("can't open the file.");
        }

        $csv_headers = fgetcsv($handle, 4000, $delimiter);
        $csv_json = array();


    

        while ($row = fgetcsv($handle, 4000, $delimiter)) {
            
            $csv_json[] = array_combine($csv_headers, $row);
        }

        

        fclose($handle);
        return json_encode($csv_json);
    }



    public function get()
    {
    }
}
