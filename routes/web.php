<?php

Route::get('/phpinfo', function()
{
	phpinfo();
});  
Route::get('laravel-version', function() 
{
    $laravel = app();
    return "Your Laravel version is ".$laravel::VERSION;
});

Route::get('', function() 
{
    return redirect( asset("admin/") );
});


Route::group(['prefix' => 'default'], function () 
{
    Route::put('rawQuery', 'DefaultCrudController@rawQuery')->name("default.rawQuery");
    Route::post('checkMasterPass', 'DefaultCrudController@checkMasterPass')->name("default.checkMasterPass");
});



Route::post('setSession', 'ParametrosController@setSession')->name("parametros.setSession");

Route::group(['prefix' => 'admin'], function () 
{
    Route::group(['prefix' => 'auth'], function () 
    {
        Route::get('renewpass/{codigo}', 'Auth\LoginController@renewpass')->name("auth.renewpass");
        Route::get('login', 'Auth\LoginController@login')->name("login");
        Route::post('logar', 'Auth\LoginController@logar')->name("logar");
        Route::post('getTenants', 'Auth\LoginController@getTenants')->name("getTenants");
        Route::put('resetPassword', 'Auth\LoginController@authResetPassword')->name("auth.resetPassword");
        Route::put('enviarEmailSenha', 'Auth\LoginController@enviarEmailSenha')->name("auth.enviarEmailSenha");
    });
    Route::group(['middleware' => 'auth'], function()
    {
        Route::group(['middleware' => 'tenantCheck'], function()
        {
            Route::get('', 'DashboardController@dashboard')->name("dashboard");
            Route::get('sobre', 'DashboardController@sobre')->name("sobre");
            Route::get('parametros', 'ParametrosController@index')->name("parametros")->middleware('can:get_parametros');
            Route::put('parametros/testeEmail', 'ParametrosController@emailTestar')->name("parametros.email.testar")->middleware('can:get_parametros');
            Route::get('parametros/email', 'ParametrosController@email')->name("parametros.email")->middleware('can:get_parametros');
            Route::put('parametros/email/put', 'ParametrosController@emailEdit')->name("parametros.email.put")->middleware('can:get_parametros');
            Route::put('parametros/put', 'ParametrosController@put')->name("parametros.put")->middleware('can:get_config_email,put_parametros');
            Route::group(['prefix' => 'cadastros'], function () 
            {
                Route::group(['prefix' => 'empresas'], function () 
                {
                    Route::get('', 'Cadastros\Principais\EmpresasController@index')->name("cadastros.principais.empresas")->middleware('can:get_tenants');
                    Route::get('create', 'Cadastros\Principais\EmpresasController@create')->name("cadastros.principais.empresas.create")->middleware('can:post_tenants');
                    Route::post('store', 'Cadastros\Principais\EmpresasController@store')->name("cadastros.principais.empresas.store")->middleware('can:post_tenants');
                    Route::get('{id}/edit', 'Cadastros\Principais\EmpresasController@edit')->name("cadastros.principais.empresas.edit")->middleware('can:put_tenants');
                    Route::get('{id}/show', 'Cadastros\Principais\EmpresasController@show')->name("cadastros.principais.empresas.show")->middleware('can:get_tenants');
                    Route::put('{id}/edit', 'Cadastros\Principais\EmpresasController@put')->name("cadastros.principais.empresas.edit")->middleware('can:put_tenants');
                    Route::delete('delete', 'Cadastros\Principais\EmpresasController@delete')->name("cadastros.principais.empresas.delete")->middleware('can:delete_tenants');
                });

                Route::group(['prefix' => 'produtos'], function () 
                {
                    Route::get('', 'Cadastros\Principais\Produtos\ProdutosController@index')->name("cadastros.principais.produtos")->middleware('can:get_produtos');
                    Route::get('create', 'Cadastros\Principais\Produtos\ProdutosController@create')->name("cadastros.principais.produtos.create")->middleware('can:post_produtos');
                    Route::post('store', 'Cadastros\Principais\Produtos\ProdutosController@store')->name("cadastros.principais.produtos.store")->middleware('can:post_produtos');
                    Route::put('put', 'Cadastros\Principais\Produtos\ProdutosController@put')->name("cadastros.principais.produtos.put")->middleware('can:put_produtos');
                    Route::get('{id}/show', 'Cadastros\Principais\Produtos\ProdutosController@show')->name("cadastros.principais.produtos.show")->middleware('can:get_produtos');
                    Route::get('{id}/edit', 'Cadastros\Principais\Produtos\ProdutosController@edit')->name("cadastros.principais.produtos.edit")->middleware('can:get_produtos');
                    Route::get('{produtoId}/sku/create', 'Cadastros\Principais\Produtos\ProdutosController@skuCreate')->name("cadastros.principais.produtos.skus.create")->middleware('can:post_produtos');
                    Route::post('{produtoId}/sku/store', 'Cadastros\Principais\Produtos\ProdutosController@skuStore')->name("cadastros.principais.produtos.skus.store")->middleware('can:post_produtos');
                    Route::get('{produtoId}/sku/{skuId}/show', 'Cadastros\Principais\Produtos\ProdutosController@showSku')->name("cadastros.principais.produtos.skus.show")->middleware('can:get_produtos');
                    Route::get('{produtoId}/sku/{skuId}/edit', 'Cadastros\Principais\Produtos\ProdutosController@editSku')->name("cadastros.principais.produtos.skus.edit")->middleware('can:put_produtos');

                    Route::group(['prefix' => 'marcas'], function () 
                    {
                        Route::get('', 'Cadastros\Principais\Produtos\MarcaProdutoController@index')->name("cadastros.principais.produtos.marcas")->middleware('can:get_marcas');
                        Route::get('get', 'Cadastros\Principais\Produtos\MarcaProdutoController@get')->name("cadastros.principais.produtos.marcas.get")->middleware('can:get_marcas');
                        Route::post('store', 'Cadastros\Principais\Produtos\MarcaProdutoController@store')->name("cadastros.principais.produtos.marcas.store")->middleware('can:post_marcas');
                        Route::put('put', 'Cadastros\Principais\Produtos\MarcaProdutoController@put')->name("cadastros.principais.produtos.marcas.put")->middleware('can:put_marcas');
                        Route::delete('delete', 'Cadastros\Principais\Produtos\MarcaProdutoController@delete')->name("cadastros.principais.produtos.marcas.delete")->middleware('can:delete_marcas');
                    });//marcas
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
                    Route::group(['prefix' => 'especificacoes'], function () 
                    {
                        Route::get('', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@index')->name("cadastros.principais.produtos.sku.especificacoes")->middleware('can:get_especificacaoProduto');
                        Route::get('get', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@get')->name("cadastros.principais.produtos.sku.especificacoes.get")->middleware('can:get_especificacaoProduto');
                        Route::post('store', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@store')->name("cadastros.principais.produtos.sku.especificacoes.store")->middleware('can:post_especificacaoProduto');
                        Route::put('put', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@put')->name("cadastros.principais.produtos.sku.especificacoes.put")->middleware('can:put_especificacaoProduto');
                        Route::delete('delete', 'Cadastros\Principais\Produtos\EspecificacoesSkuController@delete')->name("cadastros.principais.produtos.sku.especificacoes.delete")->middleware('can:delete_especificacaoProduto');
                    });//categorias
                });//usuarios

                Route::group(['prefix' => 'usuarios'], function () 
                {
                    Route::get('', 'Cadastros\Principais\Usuarios\UsuariosController@index')->name("cadastros.principais.usuarios")->middleware('can:get_usuarios');
                    Route::get('create', 'Cadastros\Principais\Usuarios\UsuariosController@create')->name("cadastros.principais.usuarios.create")->middleware('can:post_usuarios');
                    Route::post('store', 'Cadastros\Principais\Usuarios\UsuariosController@store')->name("cadastros.principais.usuarios.store")->middleware('can:post_usuarios');
                    Route::get('{id}/edit', 'Cadastros\Principais\Usuarios\UsuariosController@edit')->name("cadastros.principais.usuarios.edit")->middleware('can:put_usuarios');
                    Route::get('{id}/show', 'Cadastros\Principais\Usuarios\UsuariosController@show')->name("cadastros.principais.usuarios.show")->middleware('can:get_usuarios');
                    Route::put('{id}/edit', 'Cadastros\Principais\Usuarios\UsuariosController@put')->name("cadastros.principais.usuarios.edit")->middleware('can:put_usuarios');
                    Route::delete('delete', 'Cadastros\Principais\Usuarios\UsuariosController@delete')->name("cadastros.principais.usuarios.delete")->middleware('can:delete_usuarios');
                    Route::group(['prefix' => 'gruposacesso'], function () 
                    {   
                        Route::get('', 'Cadastros\Principais\Usuarios\GruposAcessoController@index')->name("cadastros.principais.usuarios.gruposAcesso")->middleware('can:get_gruposAcesso');
                        Route::post('store', 'Cadastros\Principais\Usuarios\GruposAcessoController@store')->name("cadastros.principais.usuarios.gruposAcesso.store")->middleware('can:post_gruposAcesso');
                        Route::get('filter', 'Cadastros\Principais\Usuarios\GruposAcessoController@filter')->name("cadastros.principais.usuarios.gruposAcesso.filter")->middleware('can:get_gruposAcesso');
                        Route::put('put', 'Cadastros\Principais\Usuarios\GruposAcessoController@put')->name("cadastros.principais.usuarios.gruposAcesso.put")->middleware('can:put_gruposAcesso');
                        Route::delete('delete', 'Cadastros\Principais\Usuarios\GruposAcessoController@delete')->name("cadastros.principais.usuarios.gruposAcesso.delete")->middleware('can:delete_gruposAcesso');
                        Route::get('get', 'Cadastros\Principais\Usuarios\GruposAcessoController@get')->name("cadastros.principais.usuarios.gruposAcesso.get")->middleware('can:get_gruposAcesso');
                    });//gruposacesso
                });//usuarios
                Route::group(['prefix' => 'auxiliares'], function () 
                {
                    Route::group(['prefix' => 'emails'], function () 
                    {
                        Route::get('get', 'Cadastros\Auxiliares\EmailsController@get')->name("cadastros.auxiliares.emails.get")->middleware('can:get_modelos_email');
                        Route::get('', 'Cadastros\Auxiliares\EmailsController@index')->name("cadastros.auxiliares.emails")->middleware('can:get_modelos_email');
                        Route::get('filter', 'Cadastros\Auxiliares\EmailsController@filter')->name("cadastros.auxiliares.emails.filter")->middleware('can:get_modelos_email');
                        Route::get('get', 'Cadastros\Auxiliares\EmailsController@get')->name("cadastros.auxiliares.emails.get")->middleware('can:get_modelos_email');
                        Route::post('store', 'Cadastros\Auxiliares\EmailsController@store')->name("cadastros.auxiliares.emails.store")->middleware('can:post_modelos_email');
                        Route::put('put', 'Cadastros\Auxiliares\EmailsController@put')->name("cadastros.auxiliares.emails.put")->middleware('can:put_modelos_email');
                        Route::delete('delete', 'Cadastros\Auxiliares\EmailsController@delete')->name("cadastros.auxiliares.emails.delete")->middleware('can:delete_modelos_email');
                    });//modelosEmail
                }); //auxiliares
            }); //cadastros
            Route::group(['prefix' => 'relatorios'], function () 
            {
                Route::group(['prefix' => 'padrao'], function () 
                {
                    Route::get('executeQuery', 'Relatorios\RelatorioController@executeQuery')->name("relatorios.padrao.executeQuery");
                    Route::get('executeQueryPaginado', 'Relatorios\RelatorioController@executeQueryPaginado')->name("relatorios.padrao.executeQueryPaginado");
                    Route::get('{id}', 'Relatorios\RelatorioController@index')->name("relatorios.padrao");
                });//padrao
            }); // relatorios
        }); //tenantCheck
    }); //auth
});//admin