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

Route::prefix('elasticsearch')->group(function(){

	Route::get('test',['uses'=>'ClientController@elasticsearchtest']);
	Route::get('data',['uses'=>'ClientController@elasticsearchdata']);
	Route::get('quries',['uses'=>'ClientController@ElasticSearchQuries']);

});


Route::prefix('elastica')->group(function(){

	Route::get('test',['uses'=>'ClientController@elasticatest']);
	Route::get('data',['uses'=>'ClientController@elasticadata']);
	Route::get('quries',['uses'=>'ClientController@ElasticaQuries']);


});