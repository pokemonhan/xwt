<?php
//额度管理
Route::group(['prefix' => 'fundOperation', 'namespace' => 'Admin\FundOperate'], function () {
    $namePrefix = 'backend-api.fundOperation.';
    $controller = 'FundOperationController@';
    Route::match(['post', 'options'], 'admins', ['as' => $namePrefix . 'admins', 'uses' => $controller . 'adminDetail']);
    Route::match(['post', 'options'], 'add-fund', ['as' => $namePrefix . 'add-fund', 'uses' => $controller . 'addFund']);
    Route::match(['post', 'options'], 'every-day-fund', ['as' => $namePrefix . 'every-day-fund', 'uses' => $controller . 'everyDayFund']);
    Route::match(['post', 'options'], 'fund-change-log', ['as' => $namePrefix . 'fund-change-log', 'uses' => $controller . 'fundChangeLog']);
});
