<?php

namespace App\Http\Controllers;

use App\Helper\FileHelper;
use App\Helper\OpenAIHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\File;

class CSVController extends Controller
{

    public $fileHelper;
    public $openAi;

    public function __construct()
    {
        $this->fileHelper = new FileHelper();
        $this->openAi = new OpenAIHelper();
    }

    public function index()
    {
        return view('csv.index');
    }

    public function store(Request $request)
    {

        // $this->validate($request, [
        //     'file' => ['required', 'mimes:csv'],
        // ]);

        // $file = $request->file('file');
        // $path = $this->fileHelper->save($file);

        // $name = pathinfo($path, PATHINFO_FILENAME);

        // $name = "16723803271258117371";
        // return redirect()->route('csv.show', $name);

        $path = public_path('csv/') . "16723803271258117371" . ".csv";
        $this->setCSVData($path);

        return redirect()->route('csv.show', "16723803271258117371");
    }


    public function setCSVData($path)
    {

        $name = pathinfo($path, PATHINFO_FILENAME);

        Cache::rememberForever($name . 'columns', function () use ($path) {
            return $getColumns = $this->fileHelper->getColumns($path);
        });

        Cache::rememberForever($name . 'data', function () use ($path) {
            return $getFileData = $this->fileHelper->csvtojson($path);
        });
    }

    public function show($fileName)
    {
        $csv_columns = Cache::get($fileName . 'columns');
        $csv_data = Cache::get($fileName . 'data');

        if ($csv_columns == null || $csv_data == null) {
            $path = public_path('csv/') . $fileName . ".csv";
            $this->setCSVData($path);

            $csv_columns = Cache::get($fileName . 'columns');
            $csv_data = Cache::get($fileName . 'data');
        }

        $string = implode("\n ", $csv_columns);
        // dd($csv_columns);

        // $openAISuggestion  = $this->openAi->getType($string);

        // $rawTypes = preg_split("/\r\n|\n|\r/", $openAISuggestion);
        // $filtered_type = [];

        // foreach ($rawTypes as $type) {
        //     if ($type != '') {
        //         preg_match_all('/\'(.*?)\'/', $type, $output_array);
        //         if (isset($output_array[1]) && count($output_array[1]) == 3) {
        //             array_push($filtered_type, $output_array[1]);
        //         }
        //     }
        // }

        // dd($filtered_type);


        $filtered_type = [
            ["bar", "Job Title", "Number of Users"],
            ["pie", "Sex", "Number of Users",],
            ["line", "Date of Birth", "Number of Users",]
        ];

        $final_charts_data = [];

        $data_collection = collect($csv_data);

        foreach ($filtered_type as $typeKey => $chart) {

            list($type, $x, $y) = $chart;


            $key_to_group_by = $this->fileHelper->createColumnKey($x);


            if (!isset($key_to_group_by, $csv_columns)) {

                $key_to_group_by = $this->fileHelper->createColumnKey($y);
                if (!isset($key_to_group_by, $csv_columns)) {
                    continue;
                }
                $key_to_group_by = $csv_columns[$key_to_group_by];
            } else {
                $key_to_group_by = $csv_columns[$key_to_group_by];
            }

            if ($type == 'bar') {
                $filtered_data  = $data_collection->groupBy($key_to_group_by);
                $labels = [];
                $data_set = [];
                foreach ($filtered_data as $key => $items) {

                    array_push($labels, $key);
                    array_push($data_set, count($items));
                }
                $final_charts_data[] = [
                    'type' => $type,
                    'sub_type' => 'bar',
                    'labels' => $labels,
                    'data_set' => $data_set
                ];
            }


            if ($type == "pie") {

                $filtered_data  = $data_collection->groupBy($key_to_group_by);

                $data_set = [];
                foreach ($filtered_data as $key => $items) {
                    array_push($data_set, [
                        'name' => $key,
                        'y' => count($items)
                    ]);
                }
                $final_charts_data[] = [
                    'type' => $type,
                    'sub_type' => 'pie',
                    'data_set' => $data_set
                ];
            }


            if ($type == "line") {

                $filtered_data  = $data_collection->groupBy($key_to_group_by);
                if (count($filtered_data) > 30) {
                    $getFirst = $data_collection->first();
                    $is_valid_date = $this->fileHelper->isValidDate($getFirst[$key_to_group_by]);

                    if ($is_valid_date) {
                        $filtered_data  = $data_collection->groupBy(function ($item, $key) use ($key_to_group_by) {
                            return date('Y', strtotime($item[$key_to_group_by]));
                        });
                    }
                }

                // dd($filtered_data);

                // dd($filtered_data);

                $labels = [];
                $data_set = [];
                foreach ($filtered_data as $key => $items) {
                    array_push($labels, $key);
                    array_push($data_set, count($items));                  
                }
                $final_charts_data[] = [
                    'type' => $type,
                    'sub_type' => 'line',
                    'data_set' => $data_set,
                    'labels' => $labels
                ];
            }
        }

        // dd($final_charts_data);

        $data = [
            'charts_data' => $final_charts_data
        ];

        return view('csv.show', $data);
    }
}
