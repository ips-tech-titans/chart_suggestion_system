<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class MainController extends Controller
{
    public function main(Request $request){
        dd('maincall');
    }
    
    public function setdatabase(Request $request){
        \Config::set('database.default', 'mysql');
        $getdefaultconnection = \Config::get('database.default');
        \Config::set('database.connections.'.$getdefaultconnection.'.host', '127.0.0.1');
        \Config::set('database.connections.'.$getdefaultconnection.'.port', 3306);
        \Config::set('database.connections.'.$getdefaultconnection.'.database', 'ipsadmin');
        \Config::set('database.connections.'.$getdefaultconnection.'.username', 'root');
        \Config::set('database.connections.'.$getdefaultconnection.'.password', '');

        try {
            $dbconnect = DB::connection()->getPDO();
            $dbname = DB::connection()->getDatabaseName();
            return response()->json(['success' => true, 'message' => 'Database Connected: '.$dbname]);
        } catch(Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error in Database Connection.']);
        }
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
        $tablename = "users";
        if(!empty($tablename)){
            $allrecords = DB::table($tablename);
            dd($allrecords);
        }
    }
}
