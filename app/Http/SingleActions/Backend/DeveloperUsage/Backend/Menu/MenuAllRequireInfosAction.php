<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class MenuAllRequireInfosAction
{
    /**
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $routeCollection = Route::getRoutes()->get();
        $routeInfo = [];
        if ($inputDatas['type'] == 0) {
            foreach ($routeCollection as $key => $r) {
                if (isset($r->action['as']) && $r->action['prefix'] !== '_debugbar') {
                    $routeShortData[$key]['url'] = $r->uri;
                    $routeShortData[$key]['controller'] = $r->action['controller'];
                    $routeShortData[$key]['route_name'] = $r->action['as'];
                    $routeInfo[] = $routeShortData[$key];
                }
            }
        } else {
            $type = [
                1 => 'backend-api',
                2 => 'web-api',
                3 => 'mobile-api',
            ];
            $routeEndKey = $type[$inputDatas['type']] ?? $type[1];
//        $firstlevelmenus = BackendSystemMenu::getFirstLevelList();

//        $editMenu = BackendSystemMenu::all();
            //        $routeMatchingName = $editMenu->where('route', '!=', '#')->keyBy('route')->toArray();
            $registeredRoute = BackendAdminRoute::pluck('route_name')->toArray();
            foreach ($routeCollection as $key => $r) {
                if (isset($r->action['as'])
                    && $r->action['prefix'] !== '_debugbar'
                    && preg_match('#^' . $routeEndKey . '#', $r->action['as']) === 1
                    && !in_array($r->action['as'], $registeredRoute)
                ) {
                    $routeShortData[$key]['url'] = $r->uri;
                    $routeShortData[$key]['controller'] = $r->action['controller'];
                    $routeShortData[$key]['route_name'] = $r->action['as'];
                    $routeInfo[] = $routeShortData[$key];
//                $routeInfo[$r->action['as']] = $r->uri;
                }
            }
        }
//        $data['firstlevelmenus'] = $firstlevelmenus;
        $data['route_info'] = $routeInfo;
//        $data['editMenu'] = $editMenu;
        //        $data['routeMatchingName'] = $routeMatchingName;
        return $contll->msgOut(true, $data);
    }
}
