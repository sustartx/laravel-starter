<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'test'], function (){
    Route::get('/', [\App\Http\Controllers\TestController::class, 'index']);
    Route::get('/redis', [\App\Http\Controllers\TestController::class, 'redis']);
    Route::get('/mysql', [\App\Http\Controllers\TestController::class, 'mysql']);
    Route::get('/mariadb', [\App\Http\Controllers\TestController::class, 'mariadb']);
    Route::get('/postgresql', [\App\Http\Controllers\TestController::class, 'postgresql']);
});
