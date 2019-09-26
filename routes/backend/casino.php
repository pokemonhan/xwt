<?php
//银行管理
Route::group(['prefix' => '', 'namespace' => 'Casino\Game'], static function () {
    Route::get('backend-api/getGame', 'Lists\GameListController@getGame')->name('getGame');
    Route::get('backend-api/seriesLists', 'Plats\GamePlatController@seriesLists')->name('seriesLists');
});
