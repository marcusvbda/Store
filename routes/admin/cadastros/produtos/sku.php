<?php
Route::get('{produtoId}/sku/create', 'Cadastros\Principais\Produtos\ProdutosController@skuCreate')->name("cadastros.principais.produtos.skus.create")->middleware('can:post_produtos');
Route::post('{produtoId}/sku/store', 'Cadastros\Principais\Produtos\ProdutosController@skuStore')->name("cadastros.principais.produtos.skus.store")->middleware('can:post_produtos');
Route::get('{produtoId}/sku/{skuId}/show', 'Cadastros\Principais\Produtos\ProdutosController@showSku')->name("cadastros.principais.produtos.skus.show")->middleware('can:get_produtos');
Route::get('{produtoId}/sku/{skuId}/edit', 'Cadastros\Principais\Produtos\ProdutosController@editSku')->name("cadastros.principais.produtos.skus.edit")->middleware('can:put_produtos');