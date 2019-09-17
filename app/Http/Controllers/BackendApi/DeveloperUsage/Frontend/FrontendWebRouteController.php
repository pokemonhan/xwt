<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendWebRouteAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendWebRouteDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendWebRouteIsOpenRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendWebRouteAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendWebRouteDeleteAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendWebRouteDetailAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendWebRouteIsOpenAction;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteController extends BackEndApiMainController
{
    /**
     * web路由列表
     * @param   FrontendWebRouteDetailAction $action
     * @return  JsonResponse
     */
    public function detail(FrontendWebRouteDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加web路由
     * @param   FrontendWebRouteAddRequest $request
     * @param   FrontendWebRouteAddAction  $action
     * @return  JsonResponse
     */
    public function add(FrontendWebRouteAddRequest $request, FrontendWebRouteAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除web路由
     * @param   FrontendWebRouteDeleteRequest $request
     * @param   FrontendWebRouteDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(FrontendWebRouteDeleteRequest $request, FrontendWebRouteDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 设置web路由是否开放
     * @param   FrontendWebRouteIsOpenRequest $request
     * @param   FrontendWebRouteIsOpenAction  $action
     * @return  JsonResponse
     */
    public function isOpen(FrontendWebRouteIsOpenRequest $request, FrontendWebRouteIsOpenAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
