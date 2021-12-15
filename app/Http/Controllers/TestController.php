<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function index()
    {
        echo 'Rand : ' . rand(1, 99);
    }

    public function redis()
    {
        try{
            $redis = Redis::connection();

            dump("Ping : " . $redis->ping());

            // ----------------------------------------------------------------------------------------------------
            // Default Test
            // ----------------------------------------------------------------------------------------------------
            $default_key = 'default_key';
            Redis::set($default_key, 'Test Data');
            $default_exists = Redis::exists($default_key);
            $default_value = Redis::get($default_key);
            dump($default_key, $default_exists, $default_value);
            // ----------------------------------------------------------------------------------------------------

            // ----------------------------------------------------------------------------------------------------
            // Cache Test
            // ----------------------------------------------------------------------------------------------------
            $cache_key = 'cache_key';
            Cache::put($cache_key,'Test Data', 60);
            $cache_value = Cache::get($cache_key);
            $cache_exists = Cache::has($cache_key);
//            Cache::clear();
            dump($cache_key, $cache_exists, $cache_value);
            // ----------------------------------------------------------------------------------------------------

            // ----------------------------------------------------------------------------------------------------
            // Session Test
            // ----------------------------------------------------------------------------------------------------
            $session_key = 'session_key';
            Session::all();
            Session::put($session_key, 'Test Data');
            $session_value = Session::get($session_key);
            $session_exists = Session::exists($session_key);
//            Session::forget();
            dump($session_key, $session_exists, $session_value);
            // ----------------------------------------------------------------------------------------------------

            // ----------------------------------------------------------------------------------------------------
            // Queue Test
            // ----------------------------------------------------------------------------------------------------
            \App\Jobs\TestJob::dispatch()->delay(now())->onConnection('redis');
            \App\Jobs\TestJob::dispatch()->delay(now())->onConnection('app')->onQueue('app');
            \App\Jobs\TestJob::dispatch()->delay(now())->onConnection('job')->onQueue('job');
            // ----------------------------------------------------------------------------------------------------

        } catch (Exception $e){
            dump(
                $e->getMessage()
            );

            dd($e);
        }
    }

    public function mysql(){
        try{


        } catch (Exception $e){
            dump(
                $e->getMessage()
            );

            dd($e);
        }
    }

    public function mariadb(){
        try{


        } catch (Exception $e){
            dump(
                $e->getMessage()
            );

            dd($e);
        }
    }

    public function postgresql(){
        try{


        } catch (Exception $e){
            dump(
                $e->getMessage()
            );

            dd($e);
        }
    }
}
