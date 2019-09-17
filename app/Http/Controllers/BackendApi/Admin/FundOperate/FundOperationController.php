<?php

namespace App\Http\Controllers\BackendApi\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationAddFundRequest;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationAdminDetailRequest;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationEveryDayFundRequest;
use App\Http\Requests\Backend\Admin\FundOperate\FundOperationFundChangeLogRequest;
use App\Http\SingleActions\Backend\Admin\FundOperate\FundOperationAddFundAction;
use App\Http\SingleActions\Backend\Admin\FundOperate\FundOperationAdminDetailAction;
use App\Http\SingleActions\Backend\Admin\FundOperate\FundOperationEveryDayFundAction;
use App\Http\SingleActions\Backend\Admin\FundOperate\FundOperationFundChangeLogAction;
use Illuminate\Http\JsonResponse;

class FundOperationController extends BackEndApiMainController
{
    /**
     * 额度管理列表
     * @param   FundOperationAdminDetailRequest  $request
     * @param   FundOperationAdminDetailAction   $action
     * @return  JsonResponse
     */
    public function adminDetail(
        FundOperationAdminDetailRequest $request,
        FundOperationAdminDetailAction $action
    ): JsonResponse {
        return $action->execute($this);
    }

    /**
     * 给管理员添加人工充值额度
     * @param   FundOperationAddFundRequest $request
     * @param   FundOperationAddFundAction  $action
     * @return  JsonResponse
     */
    public function addFund(
        FundOperationAddFundRequest $request,
        FundOperationAddFundAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 设置每日的管理员人工充值额度
     * @param   FundOperationEveryDayFundRequest $request
     * @param   FundOperationEveryDayFundAction  $action
     * @return  JsonResponse
     */
    public function everyDayFund(
        FundOperationEveryDayFundRequest $request,
        FundOperationEveryDayFundAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 查看管理员人工充值额度记录
     * @param  FundOperationFundChangeLogRequest $request
     * @param  FundOperationFundChangeLogAction  $action
     * @return JsonResponse
     */
    public function fundChangeLog(
        FundOperationFundChangeLogRequest $request,
        FundOperationFundChangeLogAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
