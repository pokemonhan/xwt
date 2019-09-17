<?php
/**
 * author: Alvin
 * Date: 5/21/2019
 * Time: 16:27 Pm
 */

//首页
Route::group(['prefix' => 'homepage', 'namespace' => 'Homepage'], static function () {
    $namePrefix = 'web-api.HomepageController.';
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
    //开奖公告列表
    Route::match(['get', 'options'], 'lottery-notice-list', [
        'as' => $namePrefix . 'lottery-notice-list',
        'uses' => $controller . 'lotteryNoticeList',
    ]);
    //活动接口
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
    //热门游戏
    Route::match(['get', 'options'], 'get-popular-game', [
        'as' => $namePrefix . 'get-popular-game',
        'uses' => $controller . 'getPopularGame',
    ]);
});
