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
Route::resource('projects', 'ProjectController')->middleware('auth');

Route::get('/projects/{project}/tasks', 'TaskController@get')->middleware('auth');
Route::post('/projects/{project}/tasks', 'TaskController@create')->middleware('auth');
Route::delete('/tasks/{task}', 'TaskController@destroy')->middleware('auth');
Route::patch('/tasks/{task}', 'TaskController@update')->middleware('auth');
