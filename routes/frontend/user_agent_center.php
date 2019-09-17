<?php

Route::group(['prefix' => 'user-agent-center'], function () {
    $namePrefix = 'web-api.UserAgentCenterController.';
    $controller = 'UserAgentCenterController@';

    Route::match(
        ['get', 'options'],
        'user-profits',
        ['as' => $namePrefix . 'user-profits', 'uses' => $controller . 'userProfits']
    );
    Route::match(
        ['get', 'options'],
        'user-daysalary',
        ['as' => $namePrefix . 'user-daysalary', 'uses' => $controller . 'userDaysalary']
    );
    //代理开户-奖金组最大最小值
    Route::match(
        ['get', 'options'],
        'prize-group',
        ['as' => $namePrefix . 'prize-group', 'uses' => $controller . 'prizeGroup']
    );
    //链接开户-链接列表
    Route::match(
        ['get', 'options'],
        'registerable-link',
        ['as' => $namePrefix . 'registerable-link', 'uses' => $controller . 'registerableLink']
    );
    //链接开户-生成链接
    Route::match(
        ['post', 'options'],
        'register-link',
        ['as' => $namePrefix . 'register-link', 'uses' => $controller . 'registerLink']
    );
    //链接开户-链接删除
    Route::match(
        ['post', 'options'],
        'link-del',
        ['as' => $namePrefix . 'link-del', 'uses' => $controller . 'linkDel']
    );
    Route::match(
        ['get', 'options'],
        'user-bonus',
        ['as' => $namePrefix . 'user-bonus', 'uses' => $controller . 'userBonus']
    );
});
