<?php
//支付方式类型表管理
Route::group(['prefix' => 'payment-type', 'namespace' => 'Admin\Payment'], static function () {
    $namePrefix = 'backend-api.payment-type.';
    $controller = 'PaymentTypesController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);//获取支付方式类型表
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'doadd']);//添加支付方式类型表
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);//编辑支付方式类型表
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);//删除支付方式类型表信息
});
