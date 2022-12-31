
<?php
class TestController extends Controller
{
    public function test()
    {
        $tables_data = $this->getTables();
        $json_ss = json_encode($tables_data);
        $promptContent = "Suggest chart types for the following data, including the recommended x and y axis in the format {{chart: '', x: '', y: ''}}: $json_ss";
        $engine = "text-davinci-003";
        $api_key = "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";
        echo $promptContent;exit;
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
        $getCurlResponse = $this->curlRequests($url, $headers, $fields, "POST");
        echo "";
        if(isset($getCurlResponse['data']) && isset($getCurlResponse['data']['choices'])){
            print_r($getCurlResponse['data']['choices'][0]['text']);
        } else {
            print_r($getCurlResponse['data']['error']['message']);
        }
    }
    public function getTables()
    {
        \Config::set('database.default', 'mysql');
		$getdefaultdatabasetype = \Config::get('database.default');
		\Config::set('database.connections.'.$getdefaultdatabasetype.'.host', '127.0.0.1');
		\Config::set('database.connections.'.$getdefaultdatabasetype.'.port', '3306');
		\Config::set('database.connections.'.$getdefaultdatabasetype.'.database', 'laravelauth');
		\Config::set('database.connections.'.$getdefaultdatabasetype.'.username', 'root');
		\Config::set('database.connections.'.$getdefaultdatabasetype.'.password', '');
		try {
            DB::connection()->getPDO();
            // var_dump('Database is connected. Database Name is : ' . \DB::connection()->getDatabaseName());
         } catch (Exception $e) {
            var_dump('Database connection failed');
         }
		DB::connection($getdefaultdatabasetype)->reconnect();
        $tables = DB::select('SHOW TABLES');
        $tables_array = [];
        foreach ($tables as $table) {
            $table_name =  head($table);
            // $tableData = DB::table($table_name)->first();
            $temp_a = $this->getColumns($table_name);
            if(count($temp_a) > 0){
                array_push($tables_array, [$temp_a]);
            }
        }
        // exit();
        return $tables_array;
    }
    public function getColumns($table_name)
    {
        $columnnames = \DB::select('show columns from ' . $table_name);
        $getdatafromselectedfields = array();
        foreach($columnnames as $key => $field){
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
            //     $getdatafromselectedfields[] = $field->Field;
        }
        $tableData = DB::table($table_name)->first();
        $temp_a = [];
        if(!empty($tableData)){
            foreach ($tableData as $colName => $colData) {
                if(in_array($colName, $getdatafromselectedfields)){
                    $temp_a['data'][$colName] = $colData;
                }
            }
        }
        return $temp_a;
    }
    public function curlRequests($url, $headers, $data, $method)
    {
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 70,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );
        if (isset($headers) && (count($headers) > 0)) {
            $curlOptions[CURLOPT_HTTPHEADER] = $headers;
        }
        if (isset($data) && (!empty($data))) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            Log::error('curl-error====>' . $err);
            return array("success" => false, 'message' => $err);
        } else {
            $response = json_decode($response, true);
            if (isset($response->error)) {
                Log::error('api-error====>' . json_encode($response));
                return array("success" => false, 'message' => $response->error);
            } else {
                return array("success" => true, 'data' => $response);
            }
        }
    }
}