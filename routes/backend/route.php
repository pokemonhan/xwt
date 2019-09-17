<?php
//开发管理-路由管理
Route::group(['prefix' => 'route', 'namespace' => 'DeveloperUsage\Backend\Routes'], function () {
    $namePrefix = 'backend-api.route.';
    $controller = 'RouteController@';
    //路由列表
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加路由
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑路由
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除路由
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    //路由开放接口
    Route::match(['post', 'options'], 'is-open', ['as' => $namePrefix . 'is-open', 'uses' => $controller . 'isOpen']);
    //前台解密接口
    Route::match(['post', 'options'], 'decrypt-front', ['as' => $namePrefix . 'decrypt-front', 'uses' => $controller . 'decryptFront']);
});
