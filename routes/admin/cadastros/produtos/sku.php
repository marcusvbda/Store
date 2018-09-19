<?php
Route::get('{produtoId}/sku', 'Cadastros\Principais\Produtos\SkuController@index')->name("cadastros.principais.produtos.skus")->middleware('can:get_produtos');
Route::get('{produtoId}/sku/create', 'Cadastros\Principais\Produtos\SkuController@create')->name("cadastros.principais.produtos.skus.create")->middleware('can:post_produtos');
Route::post('{produtoId}/sku/store', 'Cadastros\Principais\Produtos\SkuController@store')->name("cadastros.principais.produtos.skus.store")->middleware('can:post_produtos');
Route::get('{produtoId}/sku/{skuId}/show', 'Cadastros\Principais\Produtos\SkuController@show')->name("cadastros.principais.produtos.skus.show")->middleware('can:get_produtos');
Route::get('{produtoId}/sku/{skuId}/edit', 'Cadastros\Principais\Produtos\SkuController@edit')->name("cadastros.principais.produtos.skus.edit")->middleware('can:put_produtos');
Route::post('{produtoId}/sku/{skuId}/uploadImagem', 'Cadastros\Principais\Produtos\SkuController@uploadImagem')->name("cadastros.principais.produtos.skus.uploadImagem")->middleware('can:put_produtos');
Route::put('{produtoId}/sku/{skuId}/setPrincipal', 'Cadastros\Principais\Produtos\SkuController@setPrincipal')->name("cadastros.principais.produtos.skus.setPrincipal")->middleware('can:put_produtos');
Route::delete('{produtoId}/sku/{skuId}/deleteImagem', 'Cadastros\Principais\Produtos\SkuController@deleteImagem')->name("cadastros.principais.produtos.skus.deleteImagem")->middleware('can:delete_produtos');
Route::put('{produtoId}/sku/{skuId}/imagemEdit', 'Cadastros\Principais\Produtos\SkuController@imagemEdit')->name("cadastros.principais.produtos.skus.imagemEdit")->middleware('can:put_produtos');
Route::delete('{produtoId}/sku/{skuId}/delete', 'Cadastros\Principais\Produtos\SkuController@delete')->name("cadastros.principais.produtos.skus.delete")->middleware('can:delete_produtos');



