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
    $router->get('changeToHtml',[\App\Http\Controllers\Text\FileController::class,'changeToHtml']);
    $router->get('dataFilling',[\App\Http\Controllers\Text\FileController::class,'dataFilling']);
});
