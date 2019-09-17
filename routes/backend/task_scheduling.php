<?php
//开发管理-任务调度
Route::group(['prefix' => 'task-scheduling', 'namespace' => 'DeveloperUsage\TaskScheduling'], function () {
    $namePrefix = 'backend-api.TaskSchedulingController.';
    $controller = 'TaskSchedulingController@';
    //任务调度列表
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    //添加任务调度
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑任务调度
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除任务调度
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
});
