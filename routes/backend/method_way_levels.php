<?php

//开发管理-玩法等级
Route::group(['prefix' => 'method-level', 'namespace' => 'DeveloperUsage\MethodLevel'], function () {
    $namePrefix = 'backend-api.methodLevel.';
    $controller = 'MethodLevelController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
