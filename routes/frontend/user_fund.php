<?php
/**
 * @Author: Fish
 * @Date:   2019/7/2 16:12
 */

Route::group(['prefix' => 'user-fund', 'namespace' => 'User\Fund'], function () {
    $namePrefix = 'web-api.UserFundController.';
    $controller = 'UserFundController@';
    //用户账变记录
    Route::match(['post', 'options'], 'lists', ['as' => $namePrefix . 'lists', 'uses' => $controller . 'lists']);
    //用户账变类型列表
    Route::match(['post', 'options'], 'change-type-list', ['as' => $namePrefix . 'change-type-list', 'uses' => $controller . 'changeTypeList']);
});
