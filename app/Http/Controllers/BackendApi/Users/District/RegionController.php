<?php

namespace App\Http\Controllers\BackendApi\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\District\RegionAddRequest;
use App\Http\Requests\Backend\Users\District\RegionEditRequest;
use App\Http\Requests\Backend\Users\District\RegionGetTownRequest;
use App\Http\Requests\Backend\Users\District\RegionSearchTownRequest;
use App\Http\SingleActions\Backend\Users\District\RegionAddAction;
use App\Http\SingleActions\Backend\Users\District\RegionDetailAction;
use App\Http\SingleActions\Backend\Users\District\RegionEditAction;
use App\Http\SingleActions\Backend\Users\District\RegionGetTownAction;
use App\Http\SingleActions\Backend\Users\District\RegionSearchTownAction;
use Illuminate\Http\JsonResponse;

class RegionController extends BackEndApiMainController
{
    /**
     * 获取 省-市-县 列表
     * @param  RegionDetailAction $action
     * @return JsonResponse
     */
    public function detail(RegionDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取 镇(街道) 列表
     * @param  RegionGetTownRequest $request
     * @param  RegionGetTownAction  $action
     * @return JsonResponse
     */
    public function getTown(RegionGetTownRequest $request, RegionGetTownAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 模糊搜索 镇(街道)
     * @param  RegionSearchTownRequest $request
     * @param  RegionSearchTownAction  $action
     * @return JsonResponse
     */
    public function searchTown(RegionSearchTownRequest $request, RegionSearchTownAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加行政区
     * @param  RegionAddRequest $request
     * @param  RegionAddAction  $action
     * @return JsonResponse
     */
    public function add(RegionAddRequest $request, RegionAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑行政区
     * @param  RegionEditRequest $request
     * @param  RegionEditAction  $action
     * @return JsonResponse
     */
    public function edit(RegionEditRequest $request, RegionEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
