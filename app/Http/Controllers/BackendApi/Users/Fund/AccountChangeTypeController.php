<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeAddFieldRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeAddRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeDeleteRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeDelFieldRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeEditRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeModFieldRequest;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeAddAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeDeleteAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeDetailAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeEditAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeFieldAddAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeFieldDelAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeFieldDetailAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeFieldModAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeParamListAction;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeController extends BackEndApiMainController
{
    /**
     * 帐变类型列表
     * @param   AccountChangeTypeDetailAction $action
     * @return  JsonResponse
     */
    public function detail(AccountChangeTypeDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加帐变类型
     * @param   AccountChangeTypeAddRequest $request
     * @param   AccountChangeTypeAddAction  $action
     * @return  JsonResponse
     */
    public function add(AccountChangeTypeAddRequest $request, AccountChangeTypeAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑帐变类型
     * @param   AccountChangeTypeEditRequest $request
     * @param   AccountChangeTypeEditAction  $action
     * @return  JsonResponse
     */
    public function edit(AccountChangeTypeEditRequest $request, AccountChangeTypeEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除帐变类型
     * @param   AccountChangeTypeDeleteRequest $request
     * @param   AccountChangeTypeDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(AccountChangeTypeDeleteRequest $request, AccountChangeTypeDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 操作帐变类型时需要的字段列表
     * @param  AccountChangeTypeParamListAction $action
     * @return JsonResponse
     */
    public function paramList(AccountChangeTypeParamListAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 获取帐变类型字段列表
     * @param  AccountChangeTypeFieldDetailAction $action
     * @return JsonResponse
     */
    public function fieldDetail(AccountChangeTypeFieldDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 添加帐变类型字段
     * @param   AccountChangeTypeAddFieldRequest $request
     * @param  AccountChangeTypeFieldAddAction $action
     * @return JsonResponse
     */
    public function fieldAdd(
        AccountChangeTypeAddFieldRequest $request,
        AccountChangeTypeFieldAddAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 修改帐变类型字段
     * @param   AccountChangeTypeModFieldRequest $request
     * @param  AccountChangeTypeFieldModAction $action
     * @return JsonResponse
     */
    public function fieldMod(
        AccountChangeTypeModFieldRequest $request,
        AccountChangeTypeFieldModAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 删除帐变类型字段
     * @param   AccountChangeTypeDelFieldRequest $request
     * @param  AccountChangeTypeFieldDelAction $action
     * @return JsonResponse
     */
    public function fieldDel(
        AccountChangeTypeDelFieldRequest $request,
        AccountChangeTypeFieldDelAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
