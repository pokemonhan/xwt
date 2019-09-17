<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/11/2019
 * Time: 12:44 PM
 */

Route::match(['post', 'options'], 'login', ['as' => 'web-api.login', 'uses' => 'FrontendAuthController@login']);
Route::match(
    ['post', 'options'],
    'register',
    ['as' => 'web-api.register', 'uses' => 'FrontendAuthController@register']
);
//管理总代用户与玩家
Route::group(['prefix' => 'user'], function () {
    $namePrefix = 'web-api.FrontendAuthController.';
    $controller = 'FrontendAuthController@';
    //创建总代
    Route::match(
        ['post', 'options'],
        'detail',
        ['as' => $namePrefix . 'userDetail', 'uses' => $controller . 'userDetail']
    );
    Route::match(['get', 'options'], 'logout', ['as' => $namePrefix . 'logout', 'uses' => $controller . 'logout']);
    //用户修改登录密码
    Route::match(
        ['post', 'options'],
        'reset-user-password',
        ['as' => $namePrefix . 'reset-user-password', 'uses' => $controller . 'resetUserPassword']
    );
    //用户修改资金密码
    Route::match(
        ['post', 'options'],
        'reset-fund-password',
        ['as' => $namePrefix . 'reset-fund-password', 'uses' => $controller . 'resetFundPassword']
    );
    //用户设置资金密码
    Route::match(
        ['post', 'options'],
        'set-fund-password',
        ['as' => $namePrefix . 'set-fund-password', 'uses' => $controller . 'setFundPassword']
    );
    //用户个人资料列表
    Route::match(
        ['get', 'options'],
        'user-specific-infos',
        ['as' => $namePrefix . 'user-specific-infos', 'uses' => $controller . 'UserSpecificInfos']
    );
    //用户设置个人资料
    Route::match(
        ['post', 'options'],
        'reset-specific-infos',
        ['as' => $namePrefix . 'reset-specific-infos', 'uses' => $controller . 'resetSpecificInfos']
    );
});
