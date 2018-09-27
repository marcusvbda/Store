<?php
Route::group(['prefix' => 'cadastros/auxiliares/emails'], function () 
{
    Route::get('get', 'Cadastros\Auxiliares\EmailsController@get')->name("cadastros.auxiliares.emails.get")->middleware('can:get_modelos_email');
    Route::get('', 'Cadastros\Auxiliares\EmailsController@index')->name("cadastros.auxiliares.emails")->middleware('can:get_modelos_email');
    Route::get('filter', 'Cadastros\Auxiliares\EmailsController@filter')->name("cadastros.auxiliares.emails.filter")->middleware('can:get_modelos_email');
    Route::get('get', 'Cadastros\Auxiliares\EmailsController@get')->name("cadastros.auxiliares.emails.get")->middleware('can:get_modelos_email');
    Route::post('store', 'Cadastros\Auxiliares\EmailsController@store')->name("cadastros.auxiliares.emails.store")->middleware('can:post_modelos_email');
    Route::put('put', 'Cadastros\Auxiliares\EmailsController@put')->name("cadastros.auxiliares.emails.put")->middleware('can:put_modelos_email');
    Route::delete('delete', 'Cadastros\Auxiliares\EmailsController@delete')->name("cadastros.auxiliares.emails.delete")->middleware('can:delete_modelos_email');
});//modelosEmail