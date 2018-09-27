<?php
require ('routes/default.php');
Route::group(['prefix' => 'admin'], function () 
{
    require ('routes/admin/auth/login.php');
    Route::group(['middleware' => 'auth'], function()
    {
        Route::group(['middleware' => 'tenantCheck'], function()
        {
            require ('routes/admin/cadastros/cadastros.php');
            require ('routes/admin/utilitarios/utilitarios.php');
            require ('routes/admin/relatorios/relatorios.php');
            require ('routes/admin/parametros/parametros.php');
        });
    }); 
});