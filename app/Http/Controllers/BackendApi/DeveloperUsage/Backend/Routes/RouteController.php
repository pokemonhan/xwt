<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RouteAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RouteDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RouteEditRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RouteIsOpenRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RouteDecryptRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes\RouteAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes\RouteDeleteAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes\RouteDetailAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes\RouteEditAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes\RouteIsOpenAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Routes\RouteDecryptAction;
use Illuminate\Http\JsonResponse;

class RouteController extends BackEndApiMainController
{
    /**
     * 路由列表
     * @param   RouteDetailAction $action
     * @return  JsonResponse
     */
    public function detail(RouteDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加路由
     * @param   RouteAddRequest $request
     * @param   RouteAddAction  $action
     * @return  JsonResponse
     */
    public function add(RouteAddRequest $request, RouteAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑路由
     * @param   RouteEditRequest $request
     * @param   RouteEditAction  $action
     * @return  JsonResponse
     */
    public function edit(RouteEditRequest $request, RouteEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除路由
     * @param   RouteDeleteRequest $request
     * @param   RouteDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(RouteDeleteRequest $request, RouteDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 路由开放接口
     * @param  RouteIsOpenRequest $request
     * @param  RouteIsOpenAction  $action
     * @return JsonResponse
     */
    public function isOpen(RouteIsOpenRequest $request, RouteIsOpenAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 解密前台参数接口
     * @param  RouteDecryptRequest $request
     * @param  RouteDecryptAction  $action
     * @return JsonResponse
     */
    public function decryptFront(RouteDecryptRequest $request, RouteDecryptAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
