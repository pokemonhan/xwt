<?php
//报表管理
Route::group(['prefix' => 'reportManagement', 'namespace' => 'Report'], static function () {
    $namePrefix = 'backend-api.reportManagement.';
    $controller = 'ReportManagementController@';
    //玩家帐变报表
    Route::match(
        ['post', 'options'],
        'user-account-change',
        ['as' => $namePrefix . 'user-account-change', 'uses' => $controller . 'userAccountChange']
    );
    //玩家充值报表
    Route::match(
        ['post', 'options'],
        'user-recharge-history',
        ['as' => $namePrefix . 'user-recharge-history', 'uses' => $controller . 'userRechargeHistory']
    );
    //搜索时获取的帐变类型接口
    Route::match(
        ['get', 'options'],
        'account_change_type',
        ['as' => $namePrefix . 'account_change_type', 'uses' => $controller . 'accountChangeType']
    );
    //玩家注单报表
    Route::match(
        ['post', 'options'],
        'user-bets',
        ['as' => $namePrefix . 'user-bets', 'uses' => $controller . 'userBets']
    );
    //玩家追号报表
    Route::match(
        ['post', 'options'],
        'user-trace',
        ['as' => $namePrefix . 'user-trace', 'uses' => $controller . 'userTrace']
    );
});
