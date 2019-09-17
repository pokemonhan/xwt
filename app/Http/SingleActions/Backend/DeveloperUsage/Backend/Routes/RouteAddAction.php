<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use Illuminate\Http\JsonResponse;

class RouteAddAction
{
    protected $model;

    /**
     * @param  BackendAdminRoute  $backendAdminRoute
     */
    public function __construct(BackendAdminRoute $backendAdminRoute)
    {
        $this->model = $backendAdminRoute;
    }

    /**
     * 添加路由
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $routeEloq = new $this->model;
        $routeEloq->fill($inputDatas);
        $routeEloq->save();
        if ($routeEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $routeEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
