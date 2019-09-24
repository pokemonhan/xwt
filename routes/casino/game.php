<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/17/2019
 * Time: 2:17 PM
 */
//游戏接口
Route::group(['prefix' => '', 'namespace' => 'Game'], static function () {
    $namePrefix = 'casino-api.CasinoController.';
    $controller = 'CasinoController@';

    // 进入游戏
    Route::match(['post', 'options'], 'joinGame', [
        'as' => $namePrefix.'joinGame',
        'uses' => $controller.'joinGame'
    ]);

    // 查询余额
    Route::match(['post', 'options'], 'getBalance', [
        'as' => $namePrefix.'getBalance',
        'uses' => $controller.'getBalance'
    ]);

    // 转入游戏平台
    Route::match(['post', 'options'], 'transferIn', [
        'as' => $namePrefix.'transferIn',
        'uses' => $controller.'transferIn'
    ]);

    // 转出游戏平台
    Route::match(['post', 'options'], 'transferTo', [
        'as' => $namePrefix.'transferTo',
        'uses' => $controller.'transferTo'
    ]);

    // 踢线
    Route::match(['post', 'options'], 'kick', [
        'as' => $namePrefix.'kick',
        'uses' => $controller.'kick'
    ]);
});
