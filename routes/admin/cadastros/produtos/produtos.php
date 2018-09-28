<?php
Route::group(['prefix' => 'cadastros/produtos'], function () 
{
	Route::get('', 'Cadastros\Principais\Produtos\ProdutosController@index')->name("cadastros.principais.produtos")->middleware('can:get_produtos');
	Route::get('create', 'Cadastros\Principais\Produtos\ProdutosController@create')->name("cadastros.principais.produtos.create")->middleware('can:post_produtos');
	Route::post('store', 'Cadastros\Principais\Produtos\ProdutosController@store')->name("cadastros.principais.produtos.store")->middleware('can:post_produtos');
	Route::put('{id}/put', 'Cadastros\Principais\Produtos\ProdutosController@put')->name("cadastros.principais.produtos.put")->middleware('can:put_produtos');
	Route::get('{id}/show', 'Cadastros\Principais\Produtos\ProdutosController@show')->name("cadastros.principais.produtos.show")->middleware('can:get_produtos');
	Route::get('{id}/edit', 'Cadastros\Principais\Produtos\ProdutosController@edit')->name("cadastros.principais.produtos.edit")->middleware('can:get_produtos');
	Route::delete('{id}/delete', 'Cadastros\Principais\Produtos\ProdutosController@delete')->name("cadastros.principais.produtos.delete")->middleware('can:delete_produtos');
	require ('routes/admin/cadastros/produtos/sku.php');
	require ('routes/admin/cadastros/produtos/marcas.php');
    require ('routes/admin/cadastros/produtos/categorias.php');
    require ('routes/admin/cadastros/produtos/especificacoes.php');


	Route::post('upload', 'Cadastros\Principais\Produtos\ProdutosController@upload')->name("cadastros.principais.produtos.upload")->middleware('can:post_produtos');
	Route::post('confirm', 'Cadastros\Principais\Produtos\ProdutosController@confirmarUpload')->name("cadastros.principais.produtos.confirmarUpload")->middleware('can:post_produtos');



});