<?php
//域名管理
Route::group(['prefix' => 'domain', 'namespace' => 'Admin\Domain'], static function () {
    $namePrefix = 'backend-api.domain.';
    $controller = 'DomainController@';
    Route::match(['post', 'options'], 'list', ['as' => $namePrefix . 'list', 'uses' => $controller . 'list']);
    Route::match(['post', 'options'], 'add_domain', ['as' => $namePrefix . 'add_domain', 'uses' => $controller . 'addDomain']);
    Route::match(['post', 'options'], 'mod_domain', ['as' => $namePrefix . 'mod_domain', 'uses' => $controller . 'modDomain']);
    Route::match(['post', 'options'], 'del_domain', ['as' => $namePrefix . 'del_domain', 'uses' => $controller . 'delDomain']);
});
