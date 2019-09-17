<?php

//系统配置
Route::group(['prefix' => 'system', 'namespace' => 'System'], static function () {
    $namePrefix = 'mobile-api.SystemController.';
    $controller = 'SystemController@';
    //数据是否加密
    Route::match(['get', 'options'],'is-crypt-data',[
        'as' => $namePrefix . 'is-crypt-data',
        'uses' => $controller . 'isCryptData'
    ]);
});
