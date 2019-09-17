<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/3/2019
 * Time: 3:10 PM
 */

Route::group(['prefix' => 'winning-number', 'namespace' => 'Game\Lottery'], function () {
    $namePrefix = 'backend-api.lotteries.winning-number';
    $controller = 'WinningNumberController@';
    //游戏series获取接口
    Route::match(['post', 'options'], 'set', ['as' => $namePrefix . 'set', 'uses' => $controller . 'setLotteryNumber']);
});