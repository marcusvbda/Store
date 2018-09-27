<?php
Route::group(['prefix' => 'cms/camposPersonalizados'], function () 
{
	Route::get('', 'Utilitarios\Cms\CamposPersonalizadosController@index')->name("utilitarios.cms.camposPersonalizados")->middleware("can:get_campoPersonalizado");
	Route::get('get', 'Utilitarios\Cms\CamposPersonalizadosController@get')->name("utilitarios.cms.camposPersonalizados.get")->middleware("can:get_campoPersonalizado");
	Route::get('filter', 'Utilitarios\Cms\CamposPersonalizadosController@filter')->name("utilitarios.cms.camposPersonalizados.filter")->middleware("can:get_campoPersonalizado");
	Route::post('store', 'Utilitarios\Cms\CamposPersonalizadosController@store')->name("utilitarios.cms.camposPersonalizados.store")->middleware("can:post_campoPersonalizado");
	Route::put('put', 'Utilitarios\Cms\CamposPersonalizadosController@put')->name("utilitarios.cms.camposPersonalizados.put")->middleware("can:put_campoPersonalizado");
	Route::delete('delete', 'Utilitarios\Cms\CamposPersonalizadosController@delete')->name("utilitarios.cms.camposPersonalizados.delete")->middleware("can:delete_campoPersonalizado");
});
