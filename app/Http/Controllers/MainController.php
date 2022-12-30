<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CurlRequests;
use Illuminate\Support\Facades\Cache;
use DB;

class MainController extends Controller
{

    public function loadchart(){
        $databases = DB::select('SHOW DATABASES');
        return view('loadchart',['databases'=>$databases]);
    }
    public function setdatabase(Request $request){
        Cache::put('default', 'mysql');
        Cache::put('host', '127.0.0.1');
        Cache::put('port', '3306');
        Cache::put('database', $request->database);
        Cache::put('username', 'root');
        Cache::put('password', '');
        return response()->json(['success' => true]);
    }

    public function getDataFromSelectedDB(){
        $getalltables  = DB::select('SHOW TABLES');
        $tables = array();
        if(is_array($getalltables) && count($getalltables) > 0){
            foreach($getalltables as $table){
                $tables[] = head($table);
            }
            return response()->json(['success' => true, 'data' => $tables]);
        } else {
            return response()->json(['success' => false, 'message' => 'No Database Found.']);
        }
    }

    public function getDataFromSelectedTables(Request $request){
        $tables_array = array();
        $tables = [$request->database];
        foreach ($tables as $table) {
            // $table_name =  head($table);
            $table_name =  $table;
            // $tableData = DB::table($table_name)->first();
            $temp_a = $this->getColumns($table_name);
            if(count($temp_a) > 0){
                array_push($tables_array, [$temp_a]);
            }
        }
        // $getopenairesesponse = $this->senddatatoopenai($tables_array);
        // if($getopenairesesponse['success']){
        //     $rawTypes = preg_split("/\r\n|\n|\r/", $getopenairesesponse['data']);
        //     $filtered_type = [];
        //     foreach ($rawTypes as $type) {
        //         if ($type != '') {
        //             preg_match_all('/\'(.*?)\'/', $type, $output_array);
        //             if (isset($output_array[1])) {
        //                 array_push($filtered_type, $output_array[1]);
        //             }
        //         }
        //     }
            // $getopenairesesponsedata = json_decode($getopenairesesponsedata, true);
            // $getresponse = array();
            // if(count($filtered_type) > 0){
            //     foreach($filtered_type as $key => $type){
            //         $getcolumnname = array();
            //         $gettablename = $type[0];
            //         $chart_type = $type[1];
            //         foreach($type as $typekey => $fieldname){
            //             if(($typekey != 0) && ($typekey != 1)){
            //                 $getcolumnname[] = $fieldname;
            //             }
            //         }
            //         if(count($getcolumnname) > 0){
            //             $getresponse[$gettablename] = DB::table($gettablename)->select($getcolumnname)->get();
            //         }
            //     }
            // }
            return response()->json(['success' => true, 'data' => $tables_array]);
        // } else {
        //     return response()->json(['success' => false, 'message' => $getopenairesesponse['message']]);
        // }
    }

    public function getDataFromSelectedTableswithDb(Request $request){
        $tables = array();
        if(is_array($request->tables)){
            $tables = $request->tables;
        } else {
            $tables = array($request->tables);
        }
        $allData=array();
        foreach($tables as $tablename){
            $chartArray = array();
            $columnnames = DB::select('show columns from ' . $tablename);
            $getdatafromselectedfields = array();
            foreach($columnnames as $key => $field){
                /*if($field->Field != "id"){
                    if($field->Type == 'timestamp'){
                        // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'timestamp'];
                        $getdatafromselectedfields[] = $field->Field;
                    }
                    if(stristr($field->Type, 'int') == true){
                        // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'int'];
                        $getdatafromselectedfields[] = $field->Field;
                    }
                    // if(stristr($field->Type, 'varchar') == true){
                    //     // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'varchar'];
                    //     $getdatafromselectedfields[] = $field->Field;
                    // }
                    // if(stristr($field->Type, 'text') == true){
                    //     // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'text'];
                    //     $getdatafromselectedfields[] = $field->Field;
                    // }
                    // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'text'];
                        // $getdatafromselectedfields[] = $field->Field;
                }*/
                $getdatafromselectedfields[] = $field->Field;
            }
            $string = implode("\n ", $getdatafromselectedfields);
            $demochartdata = [["line","created_at","username"],["bar","created_by","status"],["pie","last_name","updated_at"]];
            $final_charts_data=array();
            foreach($demochartdata as $chartdata){
                $xaxisdata = DB::table($tablename)->pluck($chartdata[1]);
                $xaxis[] = $xaxisdata;
                $chartArray["yAxis"] = [
                    'title' => [
                        'text' => 'Years'
                    ]
                ];
                $chartArray["xAxis"] = array(
                    "name" => 'Years',
                    "categories" => $xaxis
                );
                $getdata = DB::table($tablename)->select($chartdata[1],$chartdata[2])->limit(49)->get();
                foreach($getdata as $data){
                    $series[] = array("name" => 'Data', 'data' => $data, 'type' => $chartdata[0]);
                }
                $chartArray["series"] = $series;
                dd($chartArray);
                // $final_charts_data[] = [
                //     'type' => $chartdata[0],
                //     'x_axis' => $x_axis,
                //     'series' => $series
                // ];
            }
            // echo json_encode($final_charts_data);
            // dd($final_charts_data);
            
            // $openAISuggestion  = $this->getType($string);
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

            $allData[]=$chartArray;
        }
        return response()->json(['success' => false, 'data' => '', 'chart_suggestion' => $allData]);
    }

    public function getcolumnsfromdatabase(Request $request){
        $columnnames = DB::select('show columns from ' . $request->database);
        return response()->json(['success' => true, 'data' => $columnnames]);
    }

    public function getColumns($table_name)
    {
        $columnnames = DB::select('show columns from ' . $table_name);
        $getdatafromselectedfields = array();
        foreach($columnnames as $key => $field){
            if($field->Field != "id"){
                if($field->Type == 'timestamp'){
                    // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'timestamp'];
                    $getdatafromselectedfields[] = $field->Field;
                }
                if(stristr($field->Type, 'int') == true){
                    // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'int'];
                    $getdatafromselectedfields[] = $field->Field;
                }
                if(stristr($field->Type, 'varchar') == true){
                    // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'varchar'];
                    $getdatafromselectedfields[] = $field->Field;
                }
                // if(stristr($field->Type, 'text') == true){
                //     // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'text'];
                //     $getdatafromselectedfields[] = $field->Field;
                // }
                // $selectedfieldnames[$tablename][] = ['columnname' => $field->Field, 'type'=>'text'];
                    $getdatafromselectedfields[] = $field->Field;
            }
        }
        $tableData = DB::table($table_name)->get();
        return $tableData;
    }

    public function senddatatoopenai($tables_data)
    {
        $json_ss = json_encode($tables_data);
        $promptContent = "Suggest chart types in lowercase for the following data, including the recommended x and y axis in the format {{tablename:'', chart: '', x: '', y: ''}}: $json_ss";
        $engine = "text-davinci-003";
        $api_key = \Config::get('app.openai_key');
        $fields = array(
            "prompt" => $promptContent,
            "temperature" => 0,
            "max_tokens" => 500,
            "top_p" => 1,
            "n" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0
        );
        $url = "https://api.openai.com/v1/engines/" . $engine . "/completions";
        $headers = array("authorization: Bearer " . $api_key, "content-type: application/json");

        $curlrequest = new CurlRequests();
        $getCurlResponse = $curlrequest->curlRequests($url, $headers, $fields, "POST");
        if(isset($getCurlResponse['data']) && isset($getCurlResponse['data']['choices'])){
            return ['success' => true, 'data' => $getCurlResponse['data']['choices'][0]['text']];
        } else {
            return ['success' => false, 'message' => $getCurlResponse['data']['error']['message']];
        }
    }

    public function getType($string = '')
    {   

        $promptContent = "Which charts can be created using following data. \n $string.  format: type:'',x:'',y:''";
        // $promptContent = "We have following $string data from csv. suggest all possible chart. format: chart_type:'',x-axis: '',y-axis: ''. \n Note:we are using only Line chart,Bar chart and Pie chart."; // format: chart_type:'',x-axis: '',y-axis: ''
        // $promptContent = "We have following $string data from csv. Which charts will be best. format: chart_type:'',x-axis: '',y-axis: ''."; // format: chart_type:'',x-axis: '',y-axis: ''
        // $promptContent = "Suggest Charts with X-axis and Y-axis in format: chart_type:'',x: '',y: ''. \n Our Data is: $string.";
        // $promptContent = "Suggest various Chart with X-axis and Y-axis in format: chart_type:'',x: '',y: ''. \n Data is: \n customer \n product: \n orderdate \n amount. \n Note:we are using only Line chart,Bar chart and Pie chart.";
        $engine = "text-davinci-003";
        $api_key = "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";        


        $fields = array(
            "prompt" => $promptContent,
            "temperature" => 0,
            "max_tokens" => 500,
            "top_p" => 1,
            "n" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0
        );

        $url = "https://api.openai.com/v1/engines/" . $engine . "/completions";
        $headers = array("authorization: Bearer " . $api_key, "content-type: application/json");
        $curlrequest = new CurlRequests();
        $getCurlResponse = $curlrequest->curlRequests($url, $headers, $fields, "POST");

        if (isset($getCurlResponse['data']) && isset($getCurlResponse['data']['choices'])) {            
            // echo "<pre>";
            // print_r($getCurlResponse['data']['choices'][0]['text']);
            // exit();
            return $getCurlResponse['data']['choices'][0]['text'];
        } else {
            print_r($getCurlResponse['data']['error']['message']);
        }
    }
}
