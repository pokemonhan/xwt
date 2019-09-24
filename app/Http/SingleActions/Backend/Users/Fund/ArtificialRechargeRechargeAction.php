<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\FundOperation;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\Message\BackendSystemNoticeList;
use App\Models\BackendAdminAuditFlowList;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use App\Models\User\UsersRechargeHistorie;
use App\Models\User\UsersRechargeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArtificialRechargeRechargeAction
{
    /**
     * 给用户人工充值
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $amount = (float) $inputDatas['amount'];
        $partnerAdmin = $contll->partnerAdmin;
        $userEloq = FrontendUser::find($inputDatas['id']);
        if ($userEloq === null) {
            return $contll->msgOut(false, [], '101103');
        }
        $auditFlowID = null;
        $newFund = null;
        DB::beginTransaction();
        //超级管理、普通管理员人工充值需要审核的操作
        $deduction = $this->deductionLimit($contll, $amount, $partnerAdmin, $inputDatas, $auditFlowID, $newFund);
        if ($deduction !== null) {
            return $deduction;
        }
        if ($contll->currentPartnerAccessGroup->role === '*') {
            if ($userEloq->account()->exists()) {
                $account = $userEloq->account;
                $params = [
                    'from_admin_id' => $partnerAdmin->id,
                    'user_id' => $userEloq->id,
                    'amount' => $amount,
                ];
                $result = $account->operateAccount($params, 'artificial_recharge');
                if ($result !== true) {
                    DB::rollBack();
                    return $contll->msgOut(false, [], '', $result);
                }
            } else {
                return $contll->msgOut(false, [], '100906');
            }
        }
        $compileRecharge = $this->compileRecharge($partnerAdmin, $userEloq, $amount, $auditFlowID, $newFund, $contll->currentPartnerAccessGroup->role, $contll->log_uuid);
        if ($compileRecharge['success'] === false) {
            DB::rollBack();
            return $contll->msgOut(false, [], '101102');
        }
        DB::commit();
        return $contll->msgOut(true);
    }

    /**
     * @param BackEndApiMainController $contll
     * @param float $amount
     * @param BackendAdminUser $partnerAdmin
     * @param array $inputDatas
     * @param mixed $auditFlowID
     * @return mixed
     */
    public function deductionLimit($contll, $amount, $partnerAdmin, $inputDatas, &$auditFlowID, &$newFund)
    {
        $adminFundData = BackendAdminRechargePocessAmount::where('admin_id', $partnerAdmin->id)->first();
        if ($adminFundData === null) {
            return $contll->msgOut(false, [], '101100');
        }
        if ($adminFundData->fund < $amount) {
            return $contll->msgOut(false, [], '101101');
        }
        $newFund = $adminFundData->fund - $amount; //扣除管理员额度
        $adminFundEdit = ['fund' => $newFund];
        $adminFundData->fill($adminFundEdit);
        $adminFundData->save();
        $auditFlowID = $this->insertAuditFlow($partnerAdmin->id, $partnerAdmin->name, $inputDatas['apply_note']); //插入审核表
        $this->sendMessage(); //发送站内消息 提醒有权限的管理员审核
    }

    /**
     * @param BackendAdminUser $partnerAdmin
     * @param FrontendUser $userEloq
     * @param float $amount
     * @param mixed $auditFlowID
     * @param float $newFund
     * @param string $role
     * @param string $uuid
     * @return array
     */
    public function compileRecharge($partnerAdmin, $userEloq, $amount, $auditFlowID, $newFund, $role, $uuid)
    {
        //用户 users_recharge_histories 表
        $deposit_mode = UsersRechargeHistorie::MODE_ARTIFICIAL;
        $rechargeHistory = $this->insertRechargeHistory($userEloq, $auditFlowID, $deposit_mode, $amount, $role);
        if ($rechargeHistory === false) {
            return ['success' => false];
        }

        //添加人工充值明细表
        $insertAuditFlow = $this->insertFundLog($partnerAdmin, $userEloq, $auditFlowID, $amount, $newFund, $role, $rechargeHistory->id);
        if ($insertAuditFlow === false) {
            return ['success' => false];
        }

        // 用户 users_recharge_logs 表
        $insertRechargeLog = $this->insertRechargeLog($rechargeHistory->company_order_num, $deposit_mode, $uuid);
        if ($insertRechargeLog === false) {
            return ['success' => false];
        }

        return ['success' => true];
    }
    /**
     * 插入审核表
     * @param  int    $admin_id
     * @param  string $admin_name
     * @param  string $apply_note [备注]
     * @return int
     */
    public function insertAuditFlow($admin_id, $admin_name, $apply_note): int
    {
        $insertData = [
            'admin_id' => $admin_id,
            'admin_name' => $admin_name,
            'apply_note' => $apply_note,
        ];
        $auditFlow = new BackendAdminAuditFlowList();
        $auditFlow->fill($insertData);
        $auditFlow->save();
        return $auditFlow->id;
    }

    /**
     * 发送站内消息 提醒有权限的管理员审核
     * @return void
     */
    public function sendMessage(): void
    {
        $messageObj = new InternalNoticeMessage();
        $type = BackendSystemNoticeList::AUDIT;
        $roleId = BackendSystemMenu::where('en_name', 'recharge.check')->value('id');
        $allGroup = BackendAdminAccessGroup::select('id', 'role')->get();
        $groupIds = [];
        //获取有人工充值权限的组
        foreach ($allGroup as $group) {
            if ($group->role === '*') {
                $groupIds[] = $group->id;
            } else {
                $roleArr = json_decode($group->role, true);
                if (array_key_exists($roleId, $roleArr)) {
                    $groupIds[] = $group->id;
                }
            }
        }
        //获取有人工充值权限的管理员
        $admins = BackendAdminUser::select('id', 'group_id')->whereIn('group_id', $groupIds)->get();
        if ($admins !== null) {
            $message = '有新的人工充值需要审核';
            $messageObj->insertMessage($type, $message, $admins->toArray());
        }
    }

    /**
     * 插入充值额度记录
     * @param  object $partnerAdmin [管理员eloq]
     * @param  object $userEloq [用户eloq]
     * @param  mixed $auditFlowID [backend_admin_audit_flow_lists审核表id]
     * @param  float $amount [变动的额度]
     * @param  mixed $newFund [变动后的额度]
     * @param  string $role
     * @param  int $rechargeId
     * @return bool
     */
    public function insertFundLog($partnerAdmin, $userEloq, $auditFlowID, $amount, $newFund, $role, $rechargeId): bool
    {
        $rechargeLog = new BackendAdminRechargehumanLog();
        $type = $role !== '*' ? BackendAdminRechargehumanLog::ADMIN : 3;
        $in_out = BackendAdminRechargehumanLog::DECREMENT;
        $comment = '[给用户人工充值]==>-' . $amount . '|[目前额度]==>' . $newFund;
        $fundOperationObj = new FundOperation();
        return $fundOperationObj->insertOperationDatas($rechargeLog, $type, $in_out, $partnerAdmin->id, $partnerAdmin->name, $userEloq->id, $userEloq->username, $amount, $comment, $auditFlowID, $rechargeId);
    }

    /**
     * 插入users_recharge_histories表
     * @param  object  $userEloq       [用户eloq]
     * @param  mixed   $auditFlowID    [backend_admin_audit_flow_lists审核表id]
     * @param  int     $deposit_mode   [充值模式 0自动 1手动]
     * @param  float   $amount         [金额]
     * @param  string  $role
     */
    public function insertRechargeHistory($userEloq, $auditFlowID, $deposit_mode, $amount, $role)
    {
        $userRechargeHistory = new UsersRechargeHistorie();
        $status = $role !== '*' ? UsersRechargeHistorie::STATUS_AUDIT_WAIT : UsersRechargeHistorie::STATUS_AUDIT_SUCCESS;
        $rechargeHistoryArr = $this->insertRechargeHistoryArr($userEloq->id, $userEloq->username, $userEloq->is_tester, $userEloq->top_id, $amount, $auditFlowID, $status, $deposit_mode);
        $userRechargeHistory->fill($rechargeHistoryArr);
        $userRechargeHistory->save();
        if ($userRechargeHistory->errors()->messages()) {
            return false;
        }
        return $userRechargeHistory;
    }

    /**
     * 插入users_recharge_logs表
     * @param  string   $companyOrderNum   [充值订单号]
     * @param  int      $deposit_mode      [充值模式 0自动 1手动]
     * @param  string   $log_uuid
     * @return bool
     */
    public function insertRechargeLog($companyOrderNum, $deposit_mode, $log_uuid): bool
    {
        $rchargeLogeEloq = new UsersRechargeLog();
        $log_num = $log_uuid;
        $rechargeLogArr = $this->insertRechargeLogArr($companyOrderNum, $log_num, $deposit_mode);
        $rchargeLogeEloq->fill($rechargeLogArr);
        $rchargeLogeEloq->save();
        if ($rchargeLogeEloq->errors()->messages()) {
            return false;
        }
        return true;
    }

    /**
     * 插入users_recharge_histories    人工充值 $deposit_mode=1 后面不需要在传参
     * @param  int    $user_id
     * @param  string $user_name
     * @param  int    $is_tester
     * @param  int    $top_agent
     * @param  float  $amount
     * @param  int    $audit_flow_id
     * @param  int    $status
     * @param  int    $deposit_mode
     * @param  int    $channel
     * @param  int    $payment_id      [支付通道id]
     * @param  float  $real_amount     [实际支付金额]
     * @param  float  $handlingFee     [手续费]
     * @return array
     */
    public function insertRechargeHistoryArr($user_id, $user_name, $is_tester, $top_agent, $amount, $audit_flow_id, $status, $deposit_mode, $channel = null, $payment_id = null, $real_amount = null, $handlingFee = null): array
    {
        $insertSqlArr = [
            'user_id' => $user_id,
            'user_name' => $user_name,
            'is_tester' => $is_tester,
            'top_agent' => $top_agent,
            'deposit_mode' => $deposit_mode,
            'company_order_num' => $this->createOrder(),
            'amount' => $amount,
            'audit_flow_id' => $audit_flow_id,
            'status' => $status,
        ];
        if ($deposit_mode === 0) {
            $insertDataArr = [
                'channel' => $channel,
                'payment_id' => $payment_id,
                'real_amount' => $real_amount,
                'fee' => $handlingFee,
            ];
            $insertSqlArr = array_merge($insertSqlArr, $insertDataArr);
        }
        return $insertSqlArr;
    }

    /**
     * 生成充值订单号
     * @return string
     */
    public function createOrder(): string
    {
        return 'XWP' . Str::orderedUuid()->getNodeHex();
    }

    /**
     * 插入users_recharge_logs     人工充值 $deposit_mode=1 后面不需要在传参
     * @param  string $company_order_num
     * @param  string $log_num
     * @param  int    $deposit_mode
     * @param  string $req_type_1_params
     * @param  string $req_type_2_params
     * @param  string $req_type_4_params
     * @param  int    $req_type
     * @param  float  $real_amount
     * @return array
     */
    public function insertRechargeLogArr($company_order_num, $log_num, $deposit_mode, $req_type_1_params = null, $req_type_2_params = null, $req_type_4_params = null, $req_type = null, $real_amount = null): array
    {
        $insertSqlArr = [
            'company_order_num' => $company_order_num,
            'log_num' => $log_num,
            'deposit_mode' => $deposit_mode,
        ];
        if ($deposit_mode === 0) {
            $insertDataArr = [
                'req_type_1_params' => $req_type_1_params,
                'req_type_2_params' => $req_type_2_params,
                'req_type_4_params' => $req_type_4_params,
                'req_type' => $req_type,
                'real_amount' => $real_amount,
            ];
            $insertSqlArr = array_merge($insertSqlArr, $insertDataArr);
        }
        return $insertSqlArr;
    }
}
