<?php
Route::group(['prefix' => 'auth'], function () 
{
    Route::get('renewpass/{codigo}', 'Auth\LoginController@renewpass')->name("auth.renewpass");
    Route::get('login', 'Auth\LoginController@login')->name("login");
    Route::post('logar', 'Auth\LoginController@logar')->name("logar");
    Route::post('getTenants', 'Auth\LoginController@getTenants')->name("getTenants");
    Route::put('resetPassword', 'Auth\LoginController@authResetPassword')->name("auth.resetPassword");
    Route::put('enviarEmailSenha', 'Auth\LoginController@enviarEmailSenha')->name("auth.enviarEmailSenha");
});