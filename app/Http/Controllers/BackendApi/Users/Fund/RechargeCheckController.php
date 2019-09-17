<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\RechargeCheckAuditFailureRequest;
use App\Http\Requests\Backend\Users\Fund\RechargeCheckAuditSuccessRequest;
use App\Http\SingleActions\Backend\Users\Fund\RechargeCheckAuditFailureAction;
use App\Http\SingleActions\Backend\Users\Fund\RechargeCheckAuditSuccessAction;
use App\Http\SingleActions\Backend\Users\Fund\RechargeCheckDetailAction;
use App\Http\SingleActions\Payment\PayRechargeAction;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Message\BackendSystemNoticeList;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Backend\Users\Fund\RechargeListRequest;

class RechargeCheckController extends BackEndApiMainController
{
    public $successMessage = '你的人工充值申请已通过'; //auditSuccess
    public $failureMessage = '你的人工充值申请被驳回'; //auditFailure

    /**
     * 人工充值列表
     * @param  RechargeCheckDetailAction  $action
     * @return JsonResponse
     */
    public function detail(RechargeCheckDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 审核通过
     * @param  RechargeCheckAuditSuccessRequest $request
     * @param  RechargeCheckAuditSuccessAction  $action
     * @return JsonResponse
     */
    public function auditSuccess(
        RechargeCheckAuditSuccessRequest $request,
        RechargeCheckAuditSuccessAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 审核驳回
     * @param  RechargeCheckAuditFailureRequest $request
     * @param  RechargeCheckAuditFailureAction  $action
     * @return JsonResponse
     */
    public function auditFailure(
        RechargeCheckAuditFailureRequest $request,
        RechargeCheckAuditFailureAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 审核时 修改审核表
     * @param  object $eloq         [审核表Eloq]
     * @param  object $partnerAdmin [adminEloq]
     * @param  string $auditor_note [备注]
     * @return void
     */
    public function auditFlowEdit($eloq, $partnerAdmin, $auditor_note): void
    {
        $editData = [
            'auditor_id' => $partnerAdmin->id,
            'auditor_name' => $partnerAdmin->name,
            'auditor_note' => $auditor_note,
        ];
        $eloq->fill($editData);
        $eloq->save();
    }

    /**
     * 审核后发送站内消息提醒申请人
     * @param  int     $adminId 申请人id
     * @param  string  $message 消息内容
     * @return void
     */
    public function sendMessage($adminId, $message): void
    {
        $messageObj = new InternalNoticeMessage();
        $type = BackendSystemNoticeList::AUDIT;
        $admin = BackendAdminUser::select('id', 'group_id')->find($adminId);
        if ($admin !== null) {
            $messageObj->insertMessage($type, $message, $admin->toArray());
        }
    }

    /**
     * 充值列表
     * @param PayRechargeAction $action
     * @param RechargeListRequest $request
     * @return JsonResponse
     */
    public function backRechargeList(PayRechargeAction $action, RechargeListRequest $request) : JsonResponse
    {
        return $action->backRechargeList($this, $request);
    }
}
