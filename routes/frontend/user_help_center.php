<?php

Route::group(['prefix' => 'user-help', 'namespace' => 'User\Help'], static function () {
    $namePrefix = 'web-api.UserHelpCenterController.';
    $controller = 'UserHelpCenterController@';
    //帮助中心菜单
    Route::match(['get', 'options'], 'menus', ['as' => $namePrefix . 'menus', 'uses' => $controller . 'menus']);
});
