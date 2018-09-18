<?php
Route::group(['prefix' => 'relatorios'], function () 
{
    Route::group(['prefix' => 'padrao'], function () 
    {
        Route::get('executeQuery', 'Relatorios\RelatorioController@executeQuery')->name("relatorios.padrao.executeQuery");
        Route::get('executeQueryPaginado', 'Relatorios\RelatorioController@executeQueryPaginado')->name("relatorios.padrao.executeQueryPaginado");
        Route::get('{id}', 'Relatorios\RelatorioController@index')->name("relatorios.padrao");
    });//padrao
}); // relatorios