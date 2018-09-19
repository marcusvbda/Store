<?php
Route::get('{produtoId}/sku/create', 'Cadastros\Principais\Produtos\ProdutosController@skuCreate')->name("cadastros.principais.produtos.skus.create")->middleware('can:post_produtos');
Route::post('{produtoId}/sku/store', 'Cadastros\Principais\Produtos\ProdutosController@skuStore')->name("cadastros.principais.produtos.skus.store")->middleware('can:post_produtos');
Route::get('{produtoId}/sku/{skuId}/show', 'Cadastros\Principais\Produtos\ProdutosController@showSku')->name("cadastros.principais.produtos.skus.show")->middleware('can:get_produtos');
Route::get('{produtoId}/sku/{skuId}/edit', 'Cadastros\Principais\Produtos\ProdutosController@editSku')->name("cadastros.principais.produtos.skus.edit")->middleware('can:put_produtos');
Route::post('{produtoId}/sku/{skuId}/uploadImagem', 'Cadastros\Principais\Produtos\ProdutosController@uploadImagem')->name("cadastros.principais.produtos.skus.uploadImagem")->middleware('can:put_produtos');
Route::put('{produtoId}/sku/{skuId}/setPrincipal', 'Cadastros\Principais\Produtos\ProdutosController@setPrincipal')->name("cadastros.principais.produtos.skus.setPrincipal")->middleware('can:put_produtos');
Route::delete('{produtoId}/sku/{skuId}/deleteImagem', 'Cadastros\Principais\Produtos\ProdutosController@deleteImagem')->name("cadastros.principais.produtos.skus.deleteImagem")->middleware('can:delete_produtos');
Route::put('{produtoId}/sku/{skuId}/imagemEdit', 'Cadastros\Principais\Produtos\ProdutosController@imagemEdit')->name("cadastros.principais.produtos.skus.imagemEdit")->middleware('can:put_produtos');



