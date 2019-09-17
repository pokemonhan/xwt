<?php
//银行管理
Route::group(['prefix' => 'domain', 'namespace' => 'Admin\Domain'], function () {
    $namePrefix = 'backend-api.domain.';
    $controller = 'DomainController@';
    Route::match(['post', 'options'], 'list', ['as' => $namePrefix . 'list', 'uses' => $controller . 'list']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'mod', ['as' => $namePrefix . 'mod', 'uses' => $controller . 'mod']);
    Route::match(['post', 'options'], 'del', ['as' => $namePrefix . 'del', 'uses' => $controller . 'del']);
});
