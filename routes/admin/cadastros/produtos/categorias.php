<?php
Route::group(['prefix' => 'categorias'], function () 
{
    Route::get('', 'Cadastros\Principais\Produtos\CategoriaProdutoController@index')->name("cadastros.principais.produtos.categorias")->middleware('can:get_categoriaProduto');
    Route::get('get', 'Cadastros\Principais\Produtos\CategoriaProdutoController@get')->name("cadastros.principais.produtos.categorias.get")->middleware('can:get_categoriaProduto');
    Route::post('store', 'Cadastros\Principais\Produtos\CategoriaProdutoController@store')->name("cadastros.principais.produtos.categorias.store")->middleware('can:post_categoriaProduto');
    Route::put('put', 'Cadastros\Principais\Produtos\CategoriaProdutoController@put')->name("cadastros.principais.produtos.categorias.put")->middleware('can:put_categoriaProduto');
    Route::delete('delete', 'Cadastros\Principais\Produtos\CategoriaProdutoController@delete')->name("cadastros.principais.produtos.categorias.delete")->middleware('can:delete_categoriaProduto');

    Route::post('substore', 'Cadastros\Principais\Produtos\CategoriaProdutoController@substore')->name("cadastros.principais.produtos.categorias.subcategoria.store")->middleware('can:post_categoriaProduto');
    Route::get('getsub', 'Cadastros\Principais\Produtos\CategoriaProdutoController@getsub')->name("cadastros.principais.produtos.categorias.subcategoria.get")->middleware('can:put_categoriaProduto');
    Route::put('putsub', 'Cadastros\Principais\Produtos\CategoriaProdutoController@putsub')->name("cadastros.principais.produtos.categorias.subcategoria.put")->middleware('can:put_categoriaProduto');
    Route::delete('deletesub', 'Cadastros\Principais\Produtos\CategoriaProdutoController@deletesub')->name("cadastros.principais.produtos.categorias.subcategoria.delete")->middleware('can:delete_categoriaProduto');
});//categorias