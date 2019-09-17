<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/15/2019
 * Time: 9:01 PM
 */

//管理总代用户与玩家
Route::group(['prefix' => 'log', 'namespace' => 'Admin\Log'], function () {
    $namePrefix = 'backend-api.loghandle.';
    $controller = 'HandleLogController@';
    //搜索日志列表
    Route::match(['post', 'options'], 'list', ['as' => $namePrefix . 'list', 'uses' => $controller . 'details']);
    //前台日志列表
    Route::match(['post', 'options'], 'frontend-list', ['as' => $namePrefix . 'frontend-list', 'uses' => $controller . 'frontendLogs']);
    //IP转换地址接口
    Route::match(['get', 'options'], 'get-address', ['as' => $namePrefix . 'get-address', 'uses' => $controller . 'getAddress']);
});
