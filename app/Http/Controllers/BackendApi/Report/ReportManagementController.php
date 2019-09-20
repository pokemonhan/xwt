<?php

namespace App\Http\Controllers\BackendApi\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Report\ReportManagementWithdrawRecordRequest;
use App\Http\SingleActions\Backend\Report\ReportManagementAccountChangeTypeAction;
use App\Http\SingleActions\Backend\Report\ReportManagementUserAccountChangeAction;
use App\Http\SingleActions\Backend\Report\ReportManagementUserBetsAction;
use App\Http\SingleActions\Backend\Report\ReportManagementUserRechargeHistoryAction;
use App\Http\SingleActions\Backend\Report\ReportManagementUserTraceAction;
use App\Http\Requests\Backend\Report\ReportManagementUserBetsRequest;
use App\Http\Requests\Backend\Report\ReportManagementUserAccountChangeRequest;
use App\Http\Requests\Backend\Report\ReportManagementUserTraceRequest;
use App\Http\SingleActions\Backend\Report\ReportManagementWithdrawRecordAction;
use Illuminate\Http\JsonResponse;

class ReportManagementController extends BackEndApiMainController
{
    /**
     * 玩家帐变报表
     * @param ReportManagementUserAccountChangeRequest $request
     * @param ReportManagementUserAccountChangeAction $action
     * @return  JsonResponse
     */
    public function userAccountChange(ReportManagementUserAccountChangeRequest $request, ReportManagementUserAccountChangeAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 玩家充值报表
     * @param   ReportManagementUserRechargeHistoryAction $action
     * @return  JsonResponse
     */
    public function userRechargeHistory(ReportManagementUserRechargeHistoryAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 帐变类型列表
     * @param   ReportManagementAccountChangeTypeAction $action
     * @return  JsonResponse
     */
    public function accountChangeType(ReportManagementAccountChangeTypeAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 玩家注单报表
     * @param   ReportManagementUserBetsRequest $request
     * @param   ReportManagementUserBetsAction $action
     * @return  JsonResponse
     */
    public function userBets(ReportManagementUserBetsRequest $request, ReportManagementUserBetsAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 玩家追号报表
     * @param   ReportManagementUserTraceRequest $request
     * @param   ReportManagementUserTraceAction $action
     * @return  JsonResponse
     */
    public function userTrace(ReportManagementUserTraceRequest $request, ReportManagementUserTraceAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 玩家提现记录报表
     * @param ReportManagementWithdrawRecordRequest $request
     * @param ReportManagementWithdrawRecordAction $action
     * @return JsonResponse
     */
    public function withdrawRecord(ReportManagementWithdrawRecordRequest $request, ReportManagementWithdrawRecordAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
