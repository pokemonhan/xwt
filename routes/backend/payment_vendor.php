<?php
//第三方厂商表管理
Route::group(['prefix' => 'payment-vendor', 'namespace' => 'Admin\Payment'], static function () {
    $namePrefix = 'backend-api.payment-vendor.';
    $controller = 'PaymentVendorsController@';
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);//获取第三方厂商表
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'doadd']);//添加第三方厂商表
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);//编辑第三方厂商表
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);//删除第三方厂商表信息
});
