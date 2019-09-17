<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/11/2019
 * Time: 12:44 PM
 */

//管理总代用户与玩家
Route::group(['prefix' => 'user-handle', 'namespace' => 'Users'], static function () {
    $namePrefix = 'backend-api.userhandle.';
    $controller = 'UserHandleController@';
    //创建总代
    Route::match(['post', 'options'], 'create-user', ['as' => $namePrefix . 'create-user', 'uses' => $controller . 'createUser']);
    //创建总代时获取当前平台的奖金组
    Route::match(['get', 'options'], 'prizegroup', ['as' => $namePrefix . 'prizegroup', 'uses' => $controller . 'getUserPrizeGroup']);
    //用户信息表
    Route::match(['post', 'options'], 'users-info', ['as' => $namePrefix . 'users-info', 'uses' => $controller . 'usersInfo']);
    //给用户申请密码更换
    Route::match(['post', 'options'], 'reset-password', ['as' => $namePrefix . 'reset-password', 'uses' => $controller . 'applyResetUserPassword']);
    //给用户申请资金密码更换
    Route::match(['post', 'options'], 'reset-fund-password', ['as' => $namePrefix . 'reset-fund-password', 'uses' => $controller . 'applyResetUserFundPassword']);
    //用户密码已申请列表
    Route::match(['post', 'options'], 'reset-password-list', ['as' => $namePrefix . 'reset-password-list', 'uses' => $controller . 'appliedResetUserPasswordLists']);
    //用户资金密码已申请列表
    Route::match(['post', 'options'], 'reset-fund-password-list', ['as' => $namePrefix . 'reset-fund-password-list', 'uses' => $controller . 'appliedResetUserFundPasswordLists']);
    //给用户审核密码
    Route::match(['post', 'options'], 'audit-applied-password', ['as' => $namePrefix . 'audit-applied-password', 'uses' => $controller . 'auditApplyUserPassword']);
    //给用户审核资金密码
    Route::match(['post', 'options'], 'audit-applied-fund-password', ['as' => $namePrefix . 'audit-applied-fund-password', 'uses' => $controller . 'auditApplyUserFundPassword']);
    //给用户冻结操作
    Route::match(['post', 'options'], 'deactivate', ['as' => $namePrefix . 'deactivate', 'uses' => $controller . 'deactivate']);
    //给用户冻结操作的历史
    Route::match(['post', 'options'], 'deactivated-detail', ['as' => $namePrefix . 'deactivated-detail', 'uses' => $controller . 'deactivateDetail']);
    //用户帐变记录
    Route::match(['post', 'options'], 'user_account_change', ['as' => $namePrefix . 'user_account_change', 'uses' => $controller . 'userAccountChange']);
    //用户充值记录
    Route::match(['post', 'options'], 'user_recharge_history', ['as' => $namePrefix . 'user_recharge_history', 'uses' => $controller . 'userRechargeHistory']);
    //给用户扣款
    Route::match(['post', 'options'], 'deduction_balance', ['as' => $namePrefix . 'deduction_balance', 'uses' => $controller . 'deductionBalance']);
    //用户银行卡列表
    Route::match(['post', 'options'], 'bank-card-list', ['as' => $namePrefix . 'bank-card-list', 'uses' => $controller . 'bankCardList']);
    //获取系统头像
    Route::match(['post', 'options'], 'public-avatar', ['as' => $namePrefix . 'public-avatar', 'uses' => $controller . 'publicAvatar']);
    //设定用户头像
    Route::match(['post', 'options'], 'set-user-avatar', ['as' => $namePrefix . 'set-user-avatar', 'uses' => $controller . 'setUserAvatar']);
    //删除银行卡
    Route::match(['post', 'options'], 'delete-bank-card', ['as' => $namePrefix . 'delete-bank-card', 'uses' => $controller . 'deleteBankCard']);
});
