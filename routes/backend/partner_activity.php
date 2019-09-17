<?php

//活动相关
Route::group(['prefix' => 'activity', 'namespace' => 'Admin\Activity'], function () {
    $namePrefix = 'backend-api.activity.';
    $controller = 'ActivityInfosController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);

});
