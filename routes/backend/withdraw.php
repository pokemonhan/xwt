<?php
//玩家管理-提现审核
Route::group(['prefix' => 'withdraw-check', 'namespace' => 'Users\Fund'], static function () {
    $namePrefix = 'backend-api.withdrawCheck.';
    $controller = 'WithdrawCheckController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'audit-success', ['as' => $namePrefix . 'audit-success',
        'uses' => $controller . 'auditSuccess']);
    Route::match(['post', 'options'], 'audit-failure', ['as' => $namePrefix . 'audit-failure',
        'uses' => $controller . 'auditFailure']);
    Route::match(['post', 'options'], 'withdraw-list', ['as' => $namePrefix . 'withdraw-list',
        'uses' => $controller . 'withdrawList']);
});


//提现审核
Route::group(['prefix'=>'withdraw', 'namespace'=>'Admin\Withdraw'], static function () {
    $namePrefix = 'backend-api.withdraw.';
    $controller = 'WithdrawController@';
    Route::match(['get', 'options'], 'show', ['as' => $namePrefix . 'show', 'uses' => $controller . 'show']);
});
