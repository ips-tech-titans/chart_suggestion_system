<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ChartController extends Controller
{
    public function index(){
        $tables = DB::select('SHOW TABLES');
        $emails = DB::table('users')->limit(20)->orderBy('id')->pluck('email');
        foreach ($tables as $key => $table) {
          $usercolumn =  DB::getSchemaBuilder()->getColumnListing($table->Tables_in_chartht);
		    foreach ($usercolumn as $key => $user) {
			if ($user != 'id' && $user != 'email_verified_at') {
				$db =  DB::getSchemaBuilder()->getColumnType($table->Tables_in_chartht, $user);  
				if ($db == "integer" || $db == "datetime") {
					$store[] = $user;
                    $tablename[] = $table->Tables_in_chartht;                  
				}
			}
		}  
       }
      
       $years = array();
       foreach($tablename as $keyData=> $data){  
           if($data!="migrations" && $data!="failed_jobs" && $data!="password_resets" && $data!="id"){  
           $users = DB::table($data)->select($store[$keyData])->limit(20)->get();
           if(count($users)>0)
           foreach ($users as $key => $newuser) {
            $keydata = $store[$keyData];
			  $years[][$data] = (int)date("Y", strtotime($newuser->$keydata));
              $tabledata[] = $data;
              $keyinofo[] = $keydata;
	    	}   
        }       
       }

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
       $chartArray['exporting']=array(
        "buttons" =>[
            "contextButton" => [
                "enabled" =>false
            ]
        ]
       );

       foreach($tabledata as $key  => $data){
           $file = $keyinofo[$key]; 
           $newdata[$data][$file][] = $years[$key][$data];
       }
       $arrayremove  = array_unique($tabledata);
       $newtbalename = array_unique($keyinofo);
   
       foreach($arrayremove as $arryare){
           foreach($newdata[$arryare] as $ff){
                  $uo[] = $ff;
           }
       }
       foreach($uo as $mo){
             $series[] = array("name" => 'Registration Year', 'data' => $mo, 'type' => 'line');
        }
       $chartArray["series"] = $series;
	   return view('home')->with(['tables' => $tables])->withChartarray($chartArray);
    }

}
