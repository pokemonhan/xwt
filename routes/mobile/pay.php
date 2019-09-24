<?php


Route::group(['prefix' => 'pay', 'namespace' => 'Pay'], static function () {
    $namePrefix = 'mobile-api.PayController.';
    $controller = 'PayController@';

    //查询充值渠道
    Route::match(['get', 'options'], 'get-recharge-channel', ['as' => $namePrefix . 'getRechargeChannel',
        'uses' => $controller . 'getRechargeChannel']);

    //查询充值渠道 v2.0
    Route::match(['get', 'options'], 'v2.0/get-recharge-channel', ['as' => $namePrefix . 'getRechargeChannelNew',
        'uses' => $controller . 'getRechargeChannelNew']);

    //发起充值
    Route::match(['post', 'options'], 'recharge', ['as' => $namePrefix . 'recharge',
        'uses' => $controller . 'recharge']);

    //发起充值 v2.0
    Route::match(['post', 'options'], 'v2.0/recharge', ['as' => $namePrefix . 'rechargeNew',
        'uses' => $controller . 'rechargeNew']);

    //充值回调
    Route::match(['post', 'options'], 'recharge_callback', ['as' => $namePrefix . 'recharge_callback',
        'uses' => 'PayRechargeCallbackController@rechargeCallback']);

    //发起提现
    Route::match(['post', 'options'], 'withdraw', ['as' => $namePrefix . 'withdraw',
        'uses' => $controller . 'withdraw']);

    //充值申请列表
    Route::match(['post', 'options'], 'rechargeList', ['as' => $namePrefix . 'rechargeList',
        'uses' => $controller . 'rechargeList']);
    //充值到账列表
    Route::match(['post', 'options'], 'realRechargeList', ['as' => $namePrefix . 'realRechargeList',
        'uses' => $controller . 'realRechargeList']);

    //体现申请列表
    Route::match(['post', 'options'], 'withdrawList', ['as' => $namePrefix . 'withdrawList',
        'uses' => $controller . 'withdrawList']);
    //提现到账列表
    Route::match(['post', 'options'], 'realWithdrawList', ['as' => $namePrefix . 'realWithdrawList',
        'uses' => $controller . 'realWithdrawList']);
});
