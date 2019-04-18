<?php

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

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('statistics', 'HomeController@index');

Route::resource('departments', 'departmentsController');
Route::resource('books', 'BookController');
Route::resource('users', 'UserController');

Route::resource('reports', 'ReportController');

Route::post('departments/delete/{id}','departmentsController@destroy');
Route::post('departments/update/{id}','departmentsController@update');
Route::post('books/delete/{id}','BookController@destroy');

Route::post('users/delete/{id}','UserController@destroy');
Route::get('signout','Api\AuthController@signout');
// كلية علوم وهندسة الحاسب الآلى
