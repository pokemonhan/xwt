<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAppRoute;
use Illuminate\Http\JsonResponse;

class FrontendAppRouteIsOpenAction
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
     * 设置APP路由是否开放
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $routeELoq = $this->model::find($inputDatas['id']);
        $routeELoq->is_open = $inputDatas['is_open'];
        $routeELoq->save();
        if ($routeELoq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $routeELoq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
