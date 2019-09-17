<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelDetailRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelEditRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelDeleteAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelDetailAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Frontend\FrontendAllocatedModelEditAction;
use Illuminate\Http\JsonResponse;

class FrontendAllocatedModelController extends BackEndApiMainController
{
    /**
     * 前端模块列表
     * @param   FrontendAllocatedModelDetailRequest $request
     * @param   FrontendAllocatedModelDetailAction  $action
     * @return  JsonResponse
     */
    public function detail(
        FrontendAllocatedModelDetailRequest $request,
        FrontendAllocatedModelDetailAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加前端模块
     * @param   FrontendAllocatedModelAddRequest $request
     * @param   FrontendAllocatedModelAddAction  $action
     * @return  JsonResponse
     */
    public function add(
        FrontendAllocatedModelAddRequest $request,
        FrontendAllocatedModelAddAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑前端模块
     * @param   FrontendAllocatedModelEditRequest $request
     * @param   FrontendAllocatedModelEditAction  $action
     * @return  JsonResponse
     */
    public function edit(
        FrontendAllocatedModelEditRequest $request,
        FrontendAllocatedModelEditAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除前端模块
     * @param   FrontendAllocatedModelDeleteRequest $request
     * @param   FrontendAllocatedModelDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(
        FrontendAllocatedModelDeleteRequest $request,
        FrontendAllocatedModelDeleteAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
