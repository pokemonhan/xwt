<?php
//支付方式详情表管理
Route::group(['prefix' => 'payment-info', 'namespace' => 'Admin\Payment'], static function () {
    $namePrefix = 'backend-api.payment-info.';
    $controller = 'PaymentInfosController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);//支付方式详情表
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'doadd']);//添加支付方式详情表
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);//编辑支付方式详情表
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);//删除支付方式信息
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);//排序支付方式信息
    Route::match(['post', 'options'], 'updateStatus', ['as' => $namePrefix . 'updateStatus', 'uses' => $controller . 'updateStatus']);//更新支付方式状态
});
