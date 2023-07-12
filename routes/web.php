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

Route::group(['prefix'=>'api'],function () use ($router) {
    $router->get('read',[\App\Http\Controllers\Text\FileController::class,'Read']);
    $router->group(['namespace'=>'Test'],function () use ($router) {
//        $router->get('read','FileController@Read');
    });
});
