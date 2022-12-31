<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {  
        \Schema::defaultStringLength(191); //NEW: Increase StringLength
        if(Cache::has('default')){
            \Config::set('database.default', Cache::get('default'));
            $getdefaultdatabasetype = \Config::get('database.default');
            \Config::set('database.connections.'.$getdefaultdatabasetype.'.host', Cache::get('host'));
            \Config::set('database.connections.'.$getdefaultdatabasetype.'.port', Cache::get('port'));
            \Config::set('database.connections.'.$getdefaultdatabasetype.'.database', Cache::get('database'));
            \Config::set('database.connections.'.$getdefaultdatabasetype.'.username', Cache::get('username'));
            \Config::set('database.connections.'.$getdefaultdatabasetype.'.password', Cache::get('password'));
            try {
                DB::connection()->getPDO();
                $dbconnect = DB::connection($getdefaultdatabasetype)->reconnect();
                $dbname = DB::connection()->getDatabaseName();
                return response()->json(['success' => true, 'message' => 'Database Connected: '.$dbname]);
            } catch(Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error in Database Connection.']);
            }
        }
    }
}
