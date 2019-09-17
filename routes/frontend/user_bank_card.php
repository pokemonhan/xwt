<?php

Route::group(['prefix' => 'user-bank-card', 'namespace' => 'User\Fund'], static function () {
    $namePrefix = 'web-api.UserBankCardController.';
    $controller = 'UserBankCardController@';
    //用户银行卡列表
    Route::match(['get', 'options'], 'lists', ['as' => $namePrefix . 'lists', 'uses' => $controller . 'lists']);
    //用户添加绑定银行卡
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'addCard']);
    //添加银行卡时选择的银行列表
    Route::match(['get', 'options'], 'bank-lists', ['as' => $namePrefix . 'bank-lists', 'uses' => $controller . 'bankLists']);
    //添加银行卡时选择的省份列表
    Route::match(['get', 'options'], 'province-lists', ['as' => $namePrefix . 'province-lists', 'uses' => $controller . 'provinceLists']);
    //添加银行卡时选择的城市列表
    Route::match(['post', 'options'], 'city-lists', ['as' => $namePrefix . 'city-lists', 'uses' => $controller . 'cityLists']);
    //二次添加银行卡验证资金密码与拥有者
    Route::match(['post', 'options'], 'two-add-verifiy', ['as' => $namePrefix . 'two-add-verifiy', 'uses' => $controller . 'twoAddVerifiy']);
});
