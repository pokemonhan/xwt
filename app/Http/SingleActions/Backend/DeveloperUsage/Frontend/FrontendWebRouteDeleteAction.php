<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteDeleteAction
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
     * 删除web路由
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $routeELoq = $this->model::find($inputDatas['id']);
        $routeELoq->delete();
        if ($routeELoq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $routeELoq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
