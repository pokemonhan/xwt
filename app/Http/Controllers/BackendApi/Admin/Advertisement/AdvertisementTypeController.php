<?php

namespace App\Http\Controllers\BackendApi\Admin\Advertisement;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Advertisement\AdvertisementTypeEditRequest;
use App\Http\SingleActions\Backend\Admin\Advertisement\AdvertisementTypeDetailAction;
use App\Http\SingleActions\Backend\Admin\Advertisement\AdvertisementTypeEditAction;
use Illuminate\Http\JsonResponse;

class AdvertisementTypeController extends BackEndApiMainController
{

    /**
     * 广告列表
     * @param  AdvertisementTypeDetailAction $action
     * @return JsonResponse
     */
    public function detail(AdvertisementTypeDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 编辑广告类型
     * @param  AdvertisementTypeEditRequest $request
     * @param  AdvertisementTypeEditAction $action
     * @return JsonResponse
     */
    public function edit(AdvertisementTypeEditRequest $request, AdvertisementTypeEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
