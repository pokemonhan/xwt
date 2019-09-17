<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/17/2019
 * Time: 2:17 PM
 */
//游戏接口
Route::group(['prefix' => 'lotteries', 'namespace' => 'Game\Lottery'], static function () {
    $namePrefix = 'web-api.LotteriesController.';
    $controller = 'LotteriesController@';
    //获取彩种接口
    Route::match(['post', 'options'], 'lotteryList', [
        'as' => $namePrefix.'lotteryList',
        'uses' => $controller.'lotteryList'
    ]);
    //获取彩种接口
    Route::match(['post', 'options'], 'lotteryInfo', [
        'as' => $namePrefix.'lotteryInfo',
        'uses' => $controller.'lotteryInfo'
    ]);
    //获取彩种接口
    Route::match(['post', 'options'], 'issueHistory', [
        'as' => $namePrefix.'issueHistory',
        'uses' => $controller.'issueHistory'
    ]);
    //获取可用奖期接口
    Route::match(['post', 'options'], 'availableIssues', [
        'as' => $namePrefix.'availableIssues',
        'uses' => $controller.'availableIssues'
    ]);
    //获取下注历史接口
    Route::match(['post', 'options'], 'projectHistory', [
        'as' => $namePrefix.'projectHistory',
        'uses' => $controller.'projectHistory'
    ]);
    //获取追号历史接口
    Route::match(['post', 'options'], 'tracesHistory', [
        'as' => $namePrefix.'tracesHistory',
        'uses' => $controller.'tracesHistory'
    ]);
    //游戏投注接口
    Route::match(['post', 'options'], 'bet', [
        'as' => $namePrefix.'lotteryBet',
        'uses' => $controller.'lotteryBet'
    ]);
    //终止追号
    Route::match(['post', 'options'], 'stop-trace', [
        'as' => $namePrefix.'stop-trace',
        'uses' => $controller.'stopTrace'
    ]);
    //撤销投注
    Route::match(['post', 'options'], 'cancel-bet', [
        'as' => $namePrefix.'cancel-bet',
        'uses' => $controller.'cancelBet'
    ]);
    //获取彩种上期的奖期
    Route::match(['post', 'options'], 'last-issue', [
        'as' => $namePrefix.'last-issue',
        'uses' => $controller.'lastIssue'
    ]);
    //彩种可追号的奖期列表
    Route::match(['post', 'options'], 'trace-issue-list', [
        'as' => $namePrefix.'trace-issue-list',
        'uses' => $controller.'traceIssueList',
    ]);
    //临时测试接口
    Route::match(['post', 'options'], 'setWinPrize', [
        'as' => $namePrefix.'setWinPrize',
        'uses' => $controller.'setWinPrize'
    ]);
    //获取走势图接口
    Route::match(['post', 'options'], 'trend', [
        'as' => $namePrefix . 'trend',
        'uses' => $controller . 'trend'
    ]);
});
