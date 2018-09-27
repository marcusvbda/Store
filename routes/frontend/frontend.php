<?php
Route::get('', 'Frontend\FrontendController@index')->name("frontend.index");
Route::get('categoria/{subCategoriaNome}', 'Frontend\FrontendController@subCategoria')->name("frontend.subCategoria");
Route::get('sku/{skuNome}', 'Frontend\FrontendController@paginaSku')->name("frontend.paginaSku");
