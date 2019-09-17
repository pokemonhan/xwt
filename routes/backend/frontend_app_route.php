<?php
//开发管理-app前端路由
Route::group(['prefix' => 'frontend-app-route', 'namespace' => 'DeveloperUsage\Frontend'], function () {
    $namePrefix = 'backend-api.frontendAppRoute.';
    $controller = 'FrontendAppRouteController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    Route::match(['post', 'options'], 'is-open', ['as' => $namePrefix . 'is-open', 'uses' => $controller . 'isOpen']);
});
