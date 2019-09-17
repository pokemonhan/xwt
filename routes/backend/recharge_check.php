<?php
//玩家管理-充值审核
Route::group(['prefix' => 'recharge-check', 'namespace' => 'Users\Fund'], function () {
    $namePrefix = 'backend-api.RechargeCheck.';
    $controller = 'RechargeCheckController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'audit-success',
        ['as' => $namePrefix . 'audit-success', 'uses' => $controller . 'auditSuccess']);
    Route::match(['post', 'options'], 'audit-failure',
        ['as' => $namePrefix . 'audit-failure', 'uses' => $controller . 'auditFailure']);
    //后台充值列表
    Route::match(['post', 'options'], 'recharge-list', ['as' => $namePrefix . 'recharge-list',
        'uses' => $controller . 'backRechargeList']);
});
