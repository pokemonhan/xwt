<?php
//支付方式详情表管理
Route::group(['prefix' => 'payment-config', 'namespace' => 'Admin\Payment'], static function () {
    $namePrefix = 'backend-api.payment-config.';
    $controller = 'PaymentConfigsController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);//支付配置信息
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'doadd']);//添加支付配置信息
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);//编辑支付配置信息
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);//删除支付配置信息
});
