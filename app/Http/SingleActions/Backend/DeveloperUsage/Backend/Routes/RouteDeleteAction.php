<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use Illuminate\Http\JsonResponse;

class RouteDeleteAction
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
     * 删除路由
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $routeEloq = $this->model::find($inputDatas['id']);
        $routeEloq->delete();
        if ($routeEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $routeEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
