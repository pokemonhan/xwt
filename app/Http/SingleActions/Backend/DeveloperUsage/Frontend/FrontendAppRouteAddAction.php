<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAppRoute;
use Illuminate\Http\JsonResponse;

class FrontendAppRouteAddAction
{
    protected $model;

    /**
     * @param  FrontendAppRoute  $frontendAppRoute
     */
    public function __construct(FrontendAppRoute $frontendAppRoute)
    {
        $this->model = $frontendAppRoute;
    }

    /**
     * 添加APP路由
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
