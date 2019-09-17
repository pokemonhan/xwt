<?php

//内容管理
Route::group(['prefix' => 'content', 'namespace' => 'Admin\Article'], function () {
    $namePrefix = 'backend-api.content.';
    $controller = 'ArticlesController@';
    Route::match(['post', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'detail']);
    Route::match(['post', 'options'], 'add-articles', ['as' => $namePrefix . 'add-articles', 'uses' => $controller . 'add']);
    Route::match(['post', 'options'], 'edit-articles', ['as' => $namePrefix . 'edit-articles', 'uses' => $controller . 'edit']);
    Route::match(['post', 'options'], 'delete-articles', ['as' => $namePrefix . 'delete-articles', 'uses' => $controller . 'delete']);
    Route::match(['post', 'options'], 'sort-articles', ['as' => $namePrefix . 'sort-articles', 'uses' => $controller . 'sort']);
    Route::match(['post', 'options'], 'top-articles', ['as' => $namePrefix . 'top-articles', 'uses' => $controller . 'top']);
    Route::match(['post', 'options'], 'upload-pic', ['as' => $namePrefix . 'upload-pic', 'uses' => $controller . 'uploadPic']);
    //分类管理
    Route::match(['get', 'options'], 'category', ['as' => $namePrefix . 'category', 'uses' => 'CategoryController@detail']);
    Route::match(['get', 'options'], 'category-select', ['as' => $namePrefix . 'category-select', 'uses' => 'CategoryController@select']);
});
