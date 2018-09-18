<?php
Route::group(['prefix' => 'gruposacesso'], function () 
{   
    Route::get('', 'Cadastros\Principais\Usuarios\GruposAcessoController@index')->name("cadastros.principais.usuarios.gruposAcesso")->middleware('can:get_gruposAcesso');
    Route::post('store', 'Cadastros\Principais\Usuarios\GruposAcessoController@store')->name("cadastros.principais.usuarios.gruposAcesso.store")->middleware('can:post_gruposAcesso');
    Route::get('filter', 'Cadastros\Principais\Usuarios\GruposAcessoController@filter')->name("cadastros.principais.usuarios.gruposAcesso.filter")->middleware('can:get_gruposAcesso');
    Route::put('put', 'Cadastros\Principais\Usuarios\GruposAcessoController@put')->name("cadastros.principais.usuarios.gruposAcesso.put")->middleware('can:put_gruposAcesso');
    Route::delete('delete', 'Cadastros\Principais\Usuarios\GruposAcessoController@delete')->name("cadastros.principais.usuarios.gruposAcesso.delete")->middleware('can:delete_gruposAcesso');
    Route::get('get', 'Cadastros\Principais\Usuarios\GruposAcessoController@get')->name("cadastros.principais.usuarios.gruposAcesso.get")->middleware('can:get_gruposAcesso');
});//gruposacesso