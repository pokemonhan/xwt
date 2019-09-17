<?php

Route::group(['prefix' => 'dyn-activity', 'namespace' => 'Admin\DynActivity'], static function () {
    $namePrefix = 'backend-api.dyn-activity.';
    $controller = 'DynActivityController@';
    Route::match(['get', 'options'], 'index', ['as' => $namePrefix . 'index', 'uses' => $controller . 'index']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'doadd']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'del', ['as' => $namePrefix . 'del', 'uses' => $controller . 'delete']);
});
