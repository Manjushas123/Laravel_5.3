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

Route::get('/', "test@show");
/*Route::get('users',function() {
	$users = [
	'first_name'=> 'Manjusha',
	'last_name'=> 's',
	 'company'=>'compassites'];
	return $users;
});*/
Route::get("show","test@show");
Route::get("index","test@index");
Route::post("store","test@store");
//Route::get("/","test@show");
Route::get('/delete/{id}','test@delete');
Route::get('/edit/{id}','test@edit');
Route::post('/update/{id}','test@update');
//Route::get('edit&{$id}','test@edit');





Auth::routes();

Route::get('/home', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
