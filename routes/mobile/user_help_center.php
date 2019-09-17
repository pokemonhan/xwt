<?php
/**
 * @Author: Fish
 * @Date:   2019/7/3 19:03
 */

Route::group(['prefix' => 'user-help', 'namespace' => 'User\Help'], function () {
    $namePrefix = 'mobile-api.UserHelpCenterController.';
    $controller = 'UserHelpCenterController@';
    //帮助中心菜单
    Route::match(['get', 'options'], 'menus', ['as' => $namePrefix . 'menus', 'uses' => $controller . 'menus']);

});
