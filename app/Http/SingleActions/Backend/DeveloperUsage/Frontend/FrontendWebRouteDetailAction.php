<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteDetailAction
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
     * web路由列表
     * @param   BackEndApiMainController  $contll
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $datas = $this->model::select('id', 'route_name', 'frontend_model_id', 'title', 'description', 'is_open')
            ->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
