<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\Users\Fund\RechargeCheckController;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RechargeCheckAuditSuccessAction
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
     * 审核通过
     * @param  RechargeCheckController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(RechargeCheckController $contll, array $inputDatas): JsonResponse
    {
        $rechargeLog = $this->model::find($inputDatas['id']);
        if ($rechargeLog->status !== 0) {
            return $contll->msgOut(false, [], '100900');
        }

        $user = $rechargeLog->user;
        if ($user === null) {
            return $contll->msgOut(false, [], '100901');
        }

        $auditFlow = $rechargeLog->auditFlow;
        if ($auditFlow === null) {
            return $contll->msgOut(false, [], '100904');
        }

        DB::beginTransaction();

        $compileRecharge = $this->compileRecharge($rechargeLog, $user, $auditFlow, $inputDatas, $contll->partnerAdmin, $contll);

        if ($compileRecharge['success'] === false) {
            DB::rollBack();
            return $contll->msgOut($compileRecharge['success'], [], $compileRecharge['code'], $compileRecharge['messages']);
        } else {
            //发送站内消息提醒管理员
            $contll->sendMessage($rechargeLog->admin_id, $contll->successMessage);
            DB::commit();
            return $contll->msgOut(true);
        }
    }

    public function compileRecharge($rechargeLog, $user, $auditFlow, $inputDatas, $admin, $contll)
    {
        // 修改 backend_admin_rechargehuman_logs 表 的审核状态
        $rechargeLogEdit = ['status' => BackendAdminRechargehumanLog::AUDITSUCCESS];
        $rechargeLog->fill($rechargeLogEdit);
        $rechargeLog->save();
        if ($rechargeLog->errors()->messages()) {
            return ['success' => false, 'code' => '100907', 'messages' => ''];
        }

        // 修改 users_recharge_histories 表 的审核状态
        $historyEloq = $rechargeLog->rechargeHistorie;
        if ($historyEloq === null) {
            return ['success' => false, 'code' => '100904', 'messages' => ''];
        }
        $historyEdit = ['status' => $historyEloq::STATUS_AUDIT_SUCCESS];
        $historyEloq->fill($historyEdit);
        $historyEloq->save();
        if ($historyEloq->errors()->messages()) {
            return ['success' => false, 'code' => '100907', 'messages' => ''];
        }

        //修改审核表auditFlow
        $contll->auditFlowEdit($auditFlow, $admin, $inputDatas['auditor_note']);

        //用户帐变
        if ($user->account()->exists()) {
            $account = $user->account;
            $params = [
                'from_admin_id' => $admin->id,
                'user_id' => $user->id,
                'amount' => $historyEloq->amount,
            ];
            $result = $account->operateAccount($params, 'artificial_recharge');
            if ($result !== true) {
                return ['success' => false, 'code' => '', 'messages' => $result];
            } else {
                return ['success' => true];
            }
        } else {
            return ['success' => false, 'code' => '100906', 'messages' => ''];
        }
    }
}
