<?php
Route::group(['prefix' => 'especificacoes'], function () 
{
    Route::get('', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@index')->name("cadastros.principais.produtos.sku.especificacoes")->middleware('can:get_especificacaoProduto');
    Route::get('get', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@get')->name("cadastros.principais.produtos.sku.especificacoes.get")->middleware('can:get_especificacaoProduto');
    Route::post('store', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@store')->name("cadastros.principais.produtos.sku.especificacoes.store")->middleware('can:post_especificacaoProduto');
    Route::put('put', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@put')->name("cadastros.principais.produtos.sku.especificacoes.put")->middleware('can:put_especificacaoProduto');
    Route::delete('delete', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@delete')->name("cadastros.principais.produtos.sku.especificacoes.delete")->middleware('can:delete_especificacaoProduto');
});//categorias