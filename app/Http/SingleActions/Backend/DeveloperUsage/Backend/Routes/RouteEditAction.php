<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use Illuminate\Http\JsonResponse;

class RouteEditAction
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
     * 编辑路由
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastEloq = $this->model::find($inputDatas['id']);
        $contll->editAssignment($pastEloq, $inputDatas);
        $pastEloq->save();
        if ($pastEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $pastEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
