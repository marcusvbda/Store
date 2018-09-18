<?php
Route::group(['prefix' => 'marcas'], function () 
{
    Route::get('', 'Cadastros\Principais\Produtos\MarcaProdutoController@index')->name("cadastros.principais.produtos.marcas")->middleware('can:get_marcas');
    Route::get('get', 'Cadastros\Principais\Produtos\MarcaProdutoController@get')->name("cadastros.principais.produtos.marcas.get")->middleware('can:get_marcas');
    Route::post('store', 'Cadastros\Principais\Produtos\MarcaProdutoController@store')->name("cadastros.principais.produtos.marcas.store")->middleware('can:post_marcas');
    Route::put('put', 'Cadastros\Principais\Produtos\MarcaProdutoController@put')->name("cadastros.principais.produtos.marcas.put")->middleware('can:put_marcas');
    Route::delete('delete', 'Cadastros\Principais\Produtos\MarcaProdutoController@delete')->name("cadastros.principais.produtos.marcas.delete")->middleware('can:delete_marcas');
});//marcas