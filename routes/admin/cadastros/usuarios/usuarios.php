<?php
Route::group(['prefix' => 'cadastros/usuarios'], function () 
{
    Route::get('', 'Cadastros\Principais\Usuarios\UsuariosController@index')->name("cadastros.principais.usuarios")->middleware('can:get_usuarios');
    Route::get('create', 'Cadastros\Principais\Usuarios\UsuariosController@create')->name("cadastros.principais.usuarios.create")->middleware('can:post_usuarios');
    Route::post('store', 'Cadastros\Principais\Usuarios\UsuariosController@store')->name("cadastros.principais.usuarios.store")->middleware('can:post_usuarios');
    Route::get('{id}/edit', 'Cadastros\Principais\Usuarios\UsuariosController@edit')->name("cadastros.principais.usuarios.edit")->middleware('can:put_usuarios');
    Route::get('{id}/show', 'Cadastros\Principais\Usuarios\UsuariosController@show')->name("cadastros.principais.usuarios.show")->middleware('can:get_usuarios');
    Route::put('{id}/edit', 'Cadastros\Principais\Usuarios\UsuariosController@put')->name("cadastros.principais.usuarios.edit")->middleware('can:put_usuarios');
    Route::delete('delete', 'Cadastros\Principais\Usuarios\UsuariosController@delete')->name("cadastros.principais.usuarios.delete")->middleware('can:delete_usuarios');
    require ('routes/admin/cadastros/usuarios/grupoAcesso.php');
});//usuarios