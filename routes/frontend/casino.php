<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/17/2019
 * Time: 2:17 PM
 */
//游戏娱乐城
Route::group(['prefix' => '', 'namespace' => 'Game\Casino'], static function () {
    $namePrefix = 'web-api.CasinosController.';
    $controller = 'CasinosController@';
    // 游戏列表
    Route::match(['post', 'options'], 'casinoList', [
        'as' => $namePrefix.'casinoList',
        'uses' => $controller.'casinoList',
    ]);
    // 游戏平台
    Route::match(['post', 'options'], 'casinoPlat', [
        'as' => $namePrefix.'casinoPlat',
        'uses' => $controller.'casinoPlat',
    ]);
    // 搜索游戏
    Route::match(['post', 'options'], 'searchGameCasino', [
        'as' => $namePrefix.'searchGameCasino',
        'uses' => $controller.'searchGameCasino',
    ]);
});
