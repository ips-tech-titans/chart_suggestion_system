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

        $table = DB::select('SHOW TABLES');
        $database = array_map('current',$table);
      //  dd($request->all());
     
        $emails = DB::table('users')->limit(49)->orderBy('id')->pluck('email');
       /* foreach($request->tables as $tablename){
           // $usercolumn =  DB::getSchemaBuilder()->getColumnListing($tablename);
           
            if(count($database)>0){
            foreach ($database as $key => $user) {
                if ($user != 'id' && $user != 'email_verified_at') {
                    $db =  $database->getColumnType($tablename, $user);
                    dd($db);

                    if ($db == "integer" || $db == "datetime") {
                        $store[] = $user;
                    }
                }
            }
        }       
        }*/
       



        foreach($request->tables as $tablename){
            $string = 'describe'.' '.$tablename;
            $data = DB::select($string);
         //   dd($data);

            foreach ($data as $key => $user) {
                if ($user->Field != 'id' && $user->Field != 'email_verified_at') {
                   
                 

                    if ($user->Type == "integer" || $user->Type == "timestamp") {
                        $store[] = $user;
                    }
                }
            }

            
        }
       // dd($store);

        $chartArray["chart"] = array(
			"type" => "line"
		);
		$chartArray["title"] = array(
			"text" => "Yearly Data"			
		);
		$chartArray["credits"] = array(
			"enabled" => false
		);

        $chartArray["yAxis"] = [
			'title' => [
				'text' => 'Years'
			]
		];
		$chartArray["xAxis"] = array(
			"name" => 'Years',
			"categories" => $emails
		);
        $years = [];
        foreach($store as $key => $data){
           // dd($data);
            $users = DB::table($request->tables[0])->limit(49)->pluck($data->Field);
         //   dd($users);
            if(count($users)>0){
             foreach($users as $key => $newdata){
              //  dd($newdata);
                   $years[$data->Field][]  = (int)date("Y", strtotime($newdata));
             }
            }   
         }
         $series = [];
          foreach($store as  $key => $data){
                if(isset($years[$data->Field])){
                    $series[] = array("name" => 'Registration Year', 'data' => $years[$data->Field], 'type' => 'column');

                }
         }

        $chartArray["series"] = $series;
        return response()->json(['success' => false, 'data' => $chartArray]);
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
}
