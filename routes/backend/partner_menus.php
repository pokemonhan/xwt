<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/11/2019
 * Time: 12:24 PM
 */

//菜单相关
Route::group(['prefix' => 'menu', 'namespace' => 'DeveloperUsage\Backend\Menu'], function () {
    $namePrefix = 'backend-api.menu.';
    $controller = 'MenuController@';
    //获取商户用户的所有菜单
    Route::match(['get', 'options'], 'get-all-menu', ['as' => $namePrefix . 'allPartnerMenu', 'uses' => $controller . 'getAllMenu']);
    //获取当前商户用户的菜单
    Route::match(['get', 'options'], 'current-admin-menu', ['as' => $namePrefix . 'current-admin-menu', 'uses' => $controller . 'currentPartnerMenu']);
    //所有菜单需要参数 【父级，路由名，可编辑菜单信息】
    Route::match(['post', 'options'], '/', ['as' => $namePrefix . 'allRequireInfos', 'uses' => $controller . 'allRequireInfos']);
    Route::match(['post', 'options'], '/changeParent', ['as' => $namePrefix . 'changeParent', 'uses' => $controller . 'changeParent']);
    Route::match(['post', 'options'], '/add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], '/edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], '/delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
