<?php
/**
 * @Author: Fish
 * @Date:   2019/7/4 16:43
 */

//帮助中心
Route::group(['prefix' => 'help', 'namespace' => 'Admin\Help'], function () {
    $namePrefix = 'backend-api.HelpCenterController.';
    $controller = 'HelpCenterController@';
    //列表
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});