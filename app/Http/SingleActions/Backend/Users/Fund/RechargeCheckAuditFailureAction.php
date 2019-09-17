<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\Users\Fund\RechargeCheckController;
use App\Lib\Common\FundOperation;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RechargeCheckAuditFailureAction
{
    protected $model;

    /**
     * @param  BackendAdminRechargehumanLog  $backendAdminRechargehumanLog
     */
    public function __construct(BackendAdminRechargehumanLog $backendAdminRechargehumanLog)
    {
        $this->model = $backendAdminRechargehumanLog;
    }

    /**
     * 审核驳回
     * @param  RechargeCheckController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(RechargeCheckController $contll, array $inputDatas): JsonResponse
    {
        $rechargeLog = $this->model::find($inputDatas['id']);
        if ($rechargeLog === null) {
            return $contll->msgOut(false, [], '100905');
        }
        if ($rechargeLog->status !== 0) {
            return $contll->msgOut(false, [], '100900');
        }

        $amount = $rechargeLog->amount;
        if ($amount === null) {
            return $contll->msgOut(false, [], '100906');
        }

        $adminFundData = $rechargeLog->adminAmount;
        if ($adminFundData === null) {
            return $contll->msgOut(false, [], '100903');
        }

        $historyEloq = $rechargeLog->rechargeHistorie;
        if ($historyEloq === null) {
            return $contll->msgOut(false, [], '100904');
        }

        $auditFlow = $rechargeLog->auditFlow;
        if ($auditFlow === null) {
            return $contll->msgOut(false, [], '100904');
        }

        $newFund = (float) $adminFundData->fund + (float) $amount;
        DB::beginTransaction();
        try {
            $this->compileReject($contll, $rechargeLog, $historyEloq, $auditFlow, $adminFundData, $inputDatas, $amount, $newFund);
            //发送站内消息提醒管理员
            $contll->sendMessage($rechargeLog->admin_id, $contll->failureMessage);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    public function compileReject($contll, $rechargeLog, $historyEloq, $auditFlow, $adminFundData, $inputDatas, $amount, $newFund)
    {
        // 修改 backend_admin_rechargehuman_logs 表 的审核状态
        $rechargeLogEdit = ['status' => BackendAdminRechargehumanLog::AUDITFAILURE];
        $rechargeLog->fill($rechargeLogEdit);
        $rechargeLog->save();

        // 修改 users_recharge_histories 表 的审核状态
        $historyEdit = ['status' => $historyEloq::STATUS_AUDIT_FAILURE];
        $historyEloq->fill($historyEdit);
        $historyEloq->save();

        //退还管理员人工充值额度
        $adminFundDataEdit = ['fund' => $newFund];
        $contll->auditFlowEdit($auditFlow, $contll->partnerAdmin, $inputDatas['auditor_note']);
        $adminFundData->fill($adminFundDataEdit);
        $adminFundData->save();

        //返还额度后  backend_admin_rechargehuman_logs 记录表
        $type = BackendAdminRechargehumanLog::SYSTEM;
        $in_out = BackendAdminRechargehumanLog::INCREMENT;
        $comment = '[充值审核失败额度返还]==>+' . $amount . '|[目前额度]==>' . $newFund;
        $fundOperationObj = new FundOperation();
        $fundOperationObj->insertOperationDatas(
            $this->model,
            $type,
            $in_out,
            null,
            null,
            $auditFlow->admin_id,
            $auditFlow->admin_name,
            $amount,
            $comment,
            null,
        );
    }
}
