<?php

//省市地区相关
Route::group(['prefix' => 'region', 'namespace' => 'Users\District'], function () {
    $namePrefix = 'backend-api.region.';
    $controller = 'RegionController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'get_town', ['as' => $namePrefix . 'get_town', 'uses' => $controller . 'getTown']);
    Route::match(['post', 'options'], 'search_town', ['as' => $namePrefix . 'search_town', 'uses' => $controller . 'searchTown']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
});
