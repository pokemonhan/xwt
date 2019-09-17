<?php
//主页管理-轮播图
Route::group(['prefix' => 'homepage-rotation-chart', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'backend-api.homepageRotationChart.';
    $controller = 'HomepageBannerController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);
    Route::match(['get', 'options'], 'activity-list', ['as' => $namePrefix . 'activity-list', 'uses' => $controller . 'activityList']);
    Route::match(['get', 'options'], 'pic-standard', ['as' => $namePrefix . 'pic-standard', 'uses' => $controller . 'picStandard']);
});
