<?php
Route::group(['prefix' => 'cadastros/empresas'], function () 
{
    Route::get('', 'Cadastros\Principais\EmpresasController@index')->name("cadastros.principais.empresas")->middleware('can:get_tenants');
    Route::get('create', 'Cadastros\Principais\EmpresasController@create')->name("cadastros.principais.empresas.create")->middleware('can:post_tenants');
    Route::post('store', 'Cadastros\Principais\EmpresasController@store')->name("cadastros.principais.empresas.store")->middleware('can:post_tenants');
    Route::get('{id}/edit', 'Cadastros\Principais\EmpresasController@edit')->name("cadastros.principais.empresas.edit")->middleware('can:put_tenants');
    Route::get('{id}/show', 'Cadastros\Principais\EmpresasController@show')->name("cadastros.principais.empresas.show")->middleware('can:get_tenants');
    Route::put('{id}/edit', 'Cadastros\Principais\EmpresasController@put')->name("cadastros.principais.empresas.edit")->middleware('can:put_tenants');
    Route::delete('delete', 'Cadastros\Principais\EmpresasController@delete')->name("cadastros.principais.empresas.delete")->middleware('can:delete_tenants');
});