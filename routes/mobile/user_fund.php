<?php

Route::group(['prefix' => 'user-fund', 'namespace' => 'User\Fund'], function () {
    $namePrefix = 'mobile-api.UserFundController.';
    $controller = 'UserFundController@';
    //用户账变记录
    Route::match(['post', 'options'], 'lists', [
        'as' => $namePrefix . 'lists',
        'uses' => $controller . 'lists',
    ]);
    //用户充值记录
    Route::match(['post', 'options'], 'rechargeList', [
        'as' => $namePrefix . 'rechargeList',
        'uses' => $controller . 'rechargeList',
    ]);
    //用户账变类型列表
    Route::match(['post', 'options'], 'change-type-list', [
        'as' => $namePrefix . 'change-type-list',
        'uses' => $controller . 'changeTypeList',
    ]);
});
