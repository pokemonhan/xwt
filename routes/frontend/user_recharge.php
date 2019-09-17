<?php
/**
 * @Author: Fish
 * @Date:   2019/7/2 16:12
 */

Route::group(['prefix' => 'user-recharge', 'namespace' => 'User\Recharge'], function () {
    $namePrefix = 'web-api.UserRechargeController.';
    $controller = 'UserRechargeController@';

    // 用户充值记录
    Route::match(['post', 'options'], 'rechargeList',   ['as' => $namePrefix . 'rechargeList', 'uses' => $controller . 'rechargeList']);
    Route::match(['post', 'options'], 'recharge',       ['as' => $namePrefix . 'recharge', 'uses' => $controller . 'recharge']);
});
