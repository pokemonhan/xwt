<?php
//用于接受第三方回调信息
Route::group(['prefix' => 'payment', 'namespace' => 'Admin\Payment'], static function () {
    $namePrefix = 'backend-api.payment.';
    $controller = 'CallbackController@';
    Route::any('callback/{direction}/{payment}', ['as' => $namePrefix . 'callback', 'uses' => $controller . 'callback']);
});
