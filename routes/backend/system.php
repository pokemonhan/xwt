<?php

//后台系统公用
Route::group(['prefix' => 'system', 'namespace' => 'System'], function () {
    $namePrefix = 'backend-api.SystemController.';
    $controller = 'SystemController@';
    //图片上传
    Route::match(['post', 'options'], 'upload-pic', ['as' => $namePrefix . 'upload-pic', 'uses' => $controller . 'uploadPic']);
});
