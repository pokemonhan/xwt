<?php

namespace App\Http\Controllers\BackendApi\Admin\DynActivity;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\DynActivity\DynActivityAddRequest;
use App\Http\Requests\Backend\Admin\DynActivity\DynActivityDelRequest;
use App\Http\Requests\Backend\Admin\DynActivity\DynActivityEditRequest;
use App\Http\SingleActions\Backend\Admin\DynActivity\DynActivityAddAction;
use App\Http\SingleActions\Backend\Admin\DynActivity\DynActivityDelAction;
use App\Http\SingleActions\Backend\Admin\DynActivity\DynActivityEditAction;
use App\Http\SingleActions\Backend\Admin\DynActivity\DynActivityIndexAction;
use Illuminate\Http\JsonResponse;

class DynActivityController extends BackEndApiMainController
{
    public $folderName = 'activity';

    /**
     * 动态活动列表
     * @param DynActivityIndexAction $action
     * @return JsonResponse
     */
    public function index(DynActivityIndexAction $action):JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 动态活动的添加
     * @param DynActivityAddRequest $request
     * @param DynActivityAddAction $action
     * @return JsonResponse
     */
    public function doadd(DynActivityAddRequest $request, DynActivityAddAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 动态活动编辑
     * @param DynActivityEditRequest $request
     * @param DynActivityEditAction $action
     * @return JsonResponse
     */
    public function edit(DynActivityEditRequest $request, DynActivityEditAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this,$inputDatas);
    }

    /**
     * 动态活动删除
     * @param DynActivityDelRequest $request
     * @param DynActivityDelAction $action
     * @return JsonResponse
     */
    public function delete(DynActivityDelRequest $request, DynActivityDelAction $action):JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this,$inputDatas);
    }
}
