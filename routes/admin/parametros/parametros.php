<?php
Route::get('', 'DashboardController@dashboard')->name("dashboard");
Route::get('sobre', 'DashboardController@sobre')->name("sobre");
Route::get('parametros', 'ParametrosController@index')->name("parametros")->middleware('can:get_parametros');
Route::put('parametros/testeEmail', 'ParametrosController@emailTestar')->name("parametros.email.testar")->middleware('can:get_parametros');
Route::get('parametros/email', 'ParametrosController@email')->name("parametros.email")->middleware('can:get_parametros');
Route::put('parametros/email/put', 'ParametrosController@emailEdit')->name("parametros.email.put")->middleware('can:get_parametros');
Route::put('parametros/put', 'ParametrosController@put')->name("parametros.put")->middleware('can:get_config_email,put_parametros');