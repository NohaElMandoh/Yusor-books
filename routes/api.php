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
    Route::post('books','Api\MainController@books');
    Route::post('add_dept','Api\MainController@add_dept');
    Route::get('transactions','Api\MainController@transactions');


//    register
    Route::post('register','Api\AuthController@register');

    Route::post('login','Api\AuthController@login');



 Route::group(['middleware'=>'auth:students'],function(){
     Route::post('profile','Api\AuthController@profile');
     Route::post('create_offer','Api\MainController@create_offer');
     ///get all books which made by specific student
     Route::post('books_student','Api\MainController@books_student');
     Route::post('similar_books','Api\MainController@similar_books');
     Route::post('create_bill','Api\MainController@create_bill');

     Route::post('Bills_sold','Api\MainController@Bills_sold');





 });

});