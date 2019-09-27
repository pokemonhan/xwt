<?php

Route::group(['prefix' => 'user-agent-center'], static function () {
    $namePrefix = 'mobile-api.UserAgentCenterController.';
    $controller = 'UserAgentCenterController@';

    //团队盈亏
    Route::match(
        ['get', 'options'],
        'user-profits',
        ['as' => $namePrefix . 'user-profits', 'uses' => $controller . 'UserProfits'],
    );
    //日工资
    Route::match(
        ['get', 'options'],
        'user-daysalary',
        ['as' => $namePrefix . 'user-daysalary', 'uses' => $controller . 'UserDaysalary'],
    );
    //链接开户信息
    Route::match(
        ['get', 'options'],
        'registerable-link',
        ['as' => $namePrefix . 'registerable-link', 'uses' => $controller . 'RegisterableLink'],
    );
    //生成注册链接
    Route::match(
        ['post', 'options'],
        'register-link',
        ['as' => $namePrefix . 'register-link', 'uses' => $controller . 'RegisterLink'],
    );
    //分红
    Route::match(
        ['get', 'options'],
        'user-bonus',
        ['as' => $namePrefix . 'user-bonus', 'uses' => $controller . 'UserBonus'],
    );
    //代理开户-奖金组最大最小值
    Route::match(
        ['get', 'options'],
        'prize-group',
        ['as' => $namePrefix . 'prize-group', 'uses' => $controller . 'prizeGroup'],
    );
    //链接开户-链接列表
    Route::match(
        ['get', 'options'],
        'registerable-link',
        ['as' => $namePrefix . 'registerable-link', 'uses' => $controller . 'registerableLink'],
    );
    //链接开户-生成链接
    Route::match(
        ['post', 'options'],
        'register-link',
        ['as' => $namePrefix . 'register-link', 'uses' => $controller . 'registerLink'],
    );
    //链接开户-链接删除
    Route::match(
        ['post', 'options'],
        'link-del',
        ['as' => $namePrefix . 'link-del', 'uses' => $controller . 'linkDel'],
    );
    //团队管理
    Route::match(
        ['get', 'options'],
        'team-management',
        ['as' => $namePrefix . 'team-management', 'uses' => $controller . 'teamManagement'],
    );
    //团队报表
    Route::match(
        ['get', 'options'],
        'team-report',
        ['as' => $namePrefix . 'team-report', 'uses' => $controller . 'teamReport'],
    );
    //转账给下级
    Route::match(
        ['post', 'options'],
        'transfer-to-child',
        ['as' => $namePrefix . 'transfer-to-child', 'uses' => $controller . 'transferToChild'],
    );
});
