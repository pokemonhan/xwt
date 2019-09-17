<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAppRouteAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAppRouteDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAppRouteIsOpenRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAppRouteAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAppRouteDeleteAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAppRouteDetailAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAppRouteIsOpenAction;
use Illuminate\Http\JsonResponse;

class FrontendAppRouteController extends BackEndApiMainController
{
    /**
     * APP路由列表
     * @param   FrontendAppRouteDetailAction $action
     * @return  JsonResponse
     */
    public function detail(FrontendAppRouteDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加APP路由
     * @param   FrontendAppRouteAddRequest $request
     * @param   FrontendAppRouteAddAction  $action
     * @return  JsonResponse
     */
    public function add(FrontendAppRouteAddRequest $request, FrontendAppRouteAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除APP路由
     * @param   FrontendAppRouteDeleteRequest $request
     * @param   FrontendAppRouteDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(FrontendAppRouteDeleteRequest $request, FrontendAppRouteDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 设置APP路由是否开放
     * @param   FrontendAppRouteIsOpenRequest $request
     * @param   FrontendAppRouteIsOpenAction  $action
     * @return  JsonResponse
     */
    public function isOpen(FrontendAppRouteIsOpenRequest $request, FrontendAppRouteIsOpenAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
