<?php
/**
 * author: Alvin
 * Date: 5/21/2019
 * Time: 16:27 PM
 */

//首页
Route::group(['prefix' => 'homepage', 'namespace' => 'Homepage'], function () {
    $namePrefix = 'mobile-api.HomepageController.';
    $controller = 'HomepageController@';

    //首页-需要展示的模块
    Route::match(['get', 'options'], 'show-homepage-model', [
        'as' => $namePrefix . 'show-homepage-model',
        'uses' => $controller . 'showHomepageModel',
    ]);
    //轮播图
    Route::match(['get', 'options'], 'banner', [
        'as' => $namePrefix . 'banner',
        'uses' => $controller . 'banner',
    ]);
    //热门彩票
    Route::match(['get', 'options'], 'popular-lotteries', [
        'as' => $namePrefix . 'popular-lotteries',
        'uses' => $controller . 'popularLotteries',
    ]);
    //热门玩法
    Route::match(['get', 'options'], 'popular-methods', [
        'as' => $namePrefix . 'popular-methods',
        'uses' => $controller . 'popularMethods',
    ]);
    //热门活动
    Route::match(['get', 'options'], 'activity', [
        'as' => $namePrefix . 'activity',
        'uses' => $controller . 'activity',
    ]);
    //公告|站内信 列表
    Route::match(['post', 'options'], 'notice', [
        'as' => $namePrefix . 'notice',
        'uses' => $controller . 'notice',
    ]);
    //公告|站内信 已读处理
    Route::match(['post', 'options'], 'read-message', [
        'as' => $namePrefix . 'read-message',
        'uses' => $controller . 'readMessage',
    ]);
    //中奖排行
    Route::match(['get', 'options'], 'ranking', [
        'as' => $namePrefix . 'ranking',
        'uses' => $controller . 'ranking',
    ]);
    //活动列表
    Route::match(['post', 'options'], 'activity-list', [
        'as' => $namePrefix . 'activity-list',
        'uses' => $controller . 'activityList',
    ]);
    //获取网站基本信息
    Route::match(['get', 'options'], 'get-web-info', [
        'as' => $namePrefix . 'get-web-info',
        'uses' => $controller . 'getWebInfo',
    ]);
    //获取网站基本内容
    Route::match(['get', 'options'], 'get-basic-content', [
        'as' => $namePrefix . 'get-basic-content',
        'uses' => $controller . 'getBasicContent',
    ]);
});
