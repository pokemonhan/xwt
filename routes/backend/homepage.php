<?php
//主页管理
Route::group(['prefix' => 'homepage', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'backend-api.homepage.';
    $controller = 'HomepageController@';
    //一级菜单
    Route::match(['get', 'options'], 'nav-one', ['as' => $namePrefix . 'nav-one', 'uses' => $controller . 'navOne']);
    //主题板块
    Route::match(['get', 'options'], 'page-model', ['as' => $namePrefix . 'page-model', 'uses' => $controller . 'pageModel']);
    //编辑模块
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //LOGO 和 二维码 的编辑
    Route::match(['post', 'options'], 'upload-pic', ['as' => $namePrefix . 'upload-pic', 'uses' => $controller . 'uploadPic']);
    //配置ico
    Route::match(['post', 'options'], 'upload-ico', ['as' => $namePrefix . 'upload-ico', 'uses' => $controller . 'uploadIco']);
});

//热门彩票管理
Route::group(['prefix' => 'popular-lotteries', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'backend-api.popularLotteries.';
    $controller = 'PopularLotteriesController@';
    //热门彩票列表
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加热门彩票
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑热门彩票
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除热门彩票
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    //编辑热门彩票时选择的彩票列表
    Route::match(['get', 'options'], 'lotteries-list', ['as' => $namePrefix . 'lotteries-list', 'uses' => $controller . 'lotteriesList']);
    //热门彩票排序
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);
});

//热门玩法管理
Route::group(['prefix' => 'popular-methods', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'backend-api.popularMethods.';
    $controller = 'PopularMethodsController@';
    //热门玩法列表
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加热门玩法
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑热门玩法
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除热门玩法
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    //编辑热门玩法时选择的玩法列表
    Route::match(['get', 'options'], 'methods-list', ['as' => $namePrefix . 'methods-list', 'uses' => $controller . 'methodsList']);
    //热门玩法排序
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);
});

//开奖公告
Route::group(['prefix' => 'lottery-notice', 'namespace' => 'Admin\Homepage'], function () {
    $namePrefix = 'backend-api.lottery-notice.';
    $controller = 'LotteryNoticeController@';
    //开奖公告彩种列表
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加开奖公告的彩种
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑开奖公告的彩种
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除开奖公告的彩种
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    //排序开奖公告的彩种
    Route::match(['post', 'options'], 'sort', ['as' => $namePrefix . 'sort', 'uses' => $controller . 'sort']);
});
