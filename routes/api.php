<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' =>'v1'],function(){

    Route::Post('add_book','Api\MainController@add_book');
    Route::get('authers','Api\MainController@authers');
    Route::get('departments','Api\MainController@departments');
    Route::get('books','Api\MainController@books');




});