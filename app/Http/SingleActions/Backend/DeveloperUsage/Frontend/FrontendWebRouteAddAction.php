<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteAddAction
{
    protected $model;

    /**
     * @param  FrontendWebRoute  $frontendWebRoute
     */
    public function __construct(FrontendWebRoute $frontendWebRoute)
    {
        $this->model = $frontendWebRoute;
    }

    /**
     * 添加web路由
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
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
