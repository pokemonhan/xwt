<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteIsOpenAction
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
     * 设置web路由是否开放
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $routeEloq = $this->model::find($inputDatas['id']);
        $routeEloq->is_open = $inputDatas['is_open'];
        $routeEloq->save();
        if ($routeEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $routeEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
