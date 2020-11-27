<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('signin', 'UserController@signIn');
Route::post('login', 'UserController@logIn');
Route::get('aber', 'UserController@checkConnection');
Route::get('testNow', 'FileController@now');
Route::post('store', 'FileController@storeFile')->middleware('auth:sanctum');
Route::get('ver/archivo', 'FileController@getFile')->middleware('auth:sanctum');
