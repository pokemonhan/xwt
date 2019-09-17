<?php

namespace App\Http\Controllers\BackendApi\Admin\Log;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Log\HandleLogGetAddressRequest;
use App\Http\SingleActions\Backend\Admin\Log\HandleLogDetailsAction;
use App\Http\SingleActions\Backend\Admin\Log\HandleLogFrontendLogsAction;
use App\Http\SingleActions\Backend\Admin\Log\HandleLogGetAddressAction;
use Illuminate\Http\JsonResponse;

class HandleLogController extends BackEndApiMainController
{
    /**
     * 后台日志列表
     * @param   HandleLogDetailsAction $action
     * @return  JsonResponse
     */
    public function details(HandleLogDetailsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 前台日志列表
     * @param   HandleLogFrontendLogsAction $action
     * @return  JsonResponse
     */
    public function frontendLogs(HandleLogFrontendLogsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * IP获取地址
     * @param   HandleLogGetAddressRequest $request
     * @param   HandleLogGetAddressAction $action
     * @return  JsonResponse
     */
    public function getAddress(HandleLogGetAddressRequest $request, HandleLogGetAddressAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
