<?php
//银行管理
Route::group(['prefix' => 'bank', 'namespace' => 'Admin\FundOperate'], function () {
    $namePrefix = 'backend-api.bank.';
    $controller = 'BankController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add-bank', ['as' => $namePrefix . 'add-bank', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit-bank', ['as' => $namePrefix . 'edit-bank', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete-bank', ['as' => $namePrefix . 'delete-bank', 'uses' => $controller . 'delete']);
});
