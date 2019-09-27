<?php



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Auth::routes();
Route::group(['middleware' => ['backend-api'], 'namespace' => 'BackendApi', 'prefix' => 'api'], static function () {
    Route::match(['post', 'options'], 'login', ['as' => 'backend-api.login', 'uses' => 'BackendAuthController@login']);
});
Route::group([
    'middleware' => ['backend-api'],
    'namespace' => 'BackendApi',
    'prefix' => 'api',
], static function () {
    $sRouteDir = base_path().'/routes/backend/';
    $aRouteFiles = glob($sRouteDir.'*.php');
    foreach ($aRouteFiles as $sRouteFile) {
        include $sRouteFile;
    }
    unset($aRouteFiles);
});

Route::group([
    'middleware' => ['frontend-api'],
    'namespace' => 'FrontendApi',
    'prefix' => 'web-api',
], static function () {
    $sRouteDir = base_path().'/routes/frontend/';
    $aRouteFiles = glob($sRouteDir.'*.php');
    foreach ($aRouteFiles as $sRouteFile) {
        include $sRouteFile;
    }
    unset($aRouteFiles);
});

Route::group([
    'middleware' => ['mobile-api'],
    'namespace' => 'MobileApi',
    'prefix' => 'mobile-api',
], static function () {
    $sRouteDir = base_path().'/routes/mobile/';
    $aRouteFiles = glob($sRouteDir.'*.php');
    foreach ($aRouteFiles as $sRouteFile) {
        include $sRouteFile;
    }
    unset($aRouteFiles);
});

Route::group([
    'middleware' => ['casino-api'],
    'namespace' => 'CasinoApi',
    'prefix' => 'casino-api',
], static function () {
    $sRouteDir = base_path().'/routes/casino/';
    $aRouteFiles = glob($sRouteDir.'*.php');
    foreach ($aRouteFiles as $sRouteFile) {
        include $sRouteFile;
    }
    unset($aRouteFiles);
});
