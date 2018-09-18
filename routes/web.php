<?php
require ('routes/default.php');
Route::group(['prefix' => 'admin'], function () 
{
    require ('routes/admin/auth/login.php');
    Route::group(['middleware' => 'auth'], function()
    {
        Route::group(['middleware' => 'tenantCheck'], function()
        {
            require ('routes/admin/cadastros/empresas/empresas.php');
            require ('routes/admin/parametros/parametros.php');
            require ('routes/admin/cadastros/usuarios/usuarios.php');
            require ('routes/admin/cadastros/produtos/produtos.php');
            require ('routes/admin/cadastros/auxiliares/emails.php');
            require ('routes/admin/relatorios/relatorios.php');
        });
    }); 
});