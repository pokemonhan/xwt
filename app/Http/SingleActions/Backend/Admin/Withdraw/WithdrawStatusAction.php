<?php


namespace App\Http\SingleActions\Backend\Admin\Withdraw;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Models\User\UsersWithdrawHistorie;
use App\Models\User\UsersWithdrawHistoryOpt;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class WithdrawStatusAction
{
    protected $model;
    protected $userWithdrawHistoryOptModel;
    protected $userWithdrawHistory; //用户提现记录
    protected $userWithdrawHistoryOpt; //用户提现记录对应的操作
    protected $contll;

    /**
     * WithdrawStatusAction constructor.
     * @param UsersWithdrawHistorie $usersWithdrawHistorie
     * @param UsersWithdrawHistoryOpt $usersWithdrawHistoryOpt
     */
    public function __construct(UsersWithdrawHistorie $usersWithdrawHistorie, UsersWithdrawHistoryOpt $usersWithdrawHistoryOpt)
    {
        $this->model = $usersWithdrawHistorie;
        $this->userWithdrawHistoryOptModel = $usersWithdrawHistoryOpt;
    }
    //初始化
    public function initVarEnv($inputDatas)
    {
        $this->userWithdrawHistory = $this->model::where('id', $inputDatas['id'])->first(); //提现记录
        $this->userWithdrawHistoryOpt = $this->userWithdrawHistory->withdrawHistoryOpt;
    }
    public function execute(WithdrawController $contll, array $inputDatas) :JsonResponse
    {
        $flag = false;
        $this->contll = $contll;
        $this->initVarEnv($inputDatas);
        try {
            switch ($inputDatas['status']) {
                case $this->model::STATUS_CLAIMED: //认领
                    $flag = $this->takeOver($inputDatas);
                    break;
                case $this->model::STATUS_AUDIT_FAILURE: //驳回
                    $flag = $this->turnDown($inputDatas);
                    break;
                case $this->model::STATUS_AUDIT_SUCCESS: //通过
                    $flag = $this->passed($inputDatas);
                    break;
                case $this->model::STATUS_SUCCESS:
                    $flag = $this->toSuccess($inputDatas);
                    break;
                case $this->model::STATUS_FAIL:
                    $flag = $this->toFail($inputDatas);
                    break;
                default:
                    return $contll->msgOut(false, [], '400', '状态不合法');
                    break;
            }
            if ($flag) {
                return $contll->msgOut(true);
            } else {
                return $contll->msgOut(false, [], '400', '执行失败');
            }
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], '400', '系统错误');
        }
    }

    /**
     * 接手处理
     * @param array $inputDatas
     * @return bool
     * @throws \Exception
     */
    private function takeOver($inputDatas) :bool
    {
        if (!isset($this->userWithdrawHistoryOpt) && (int) $this->userWithdrawHistory->status === $this->model::STATUS_AUDIT_WAIT) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $this->model::STATUS_CLAIMED;
            $this->userWithdrawHistoryOptModel->withdraw_id = $this->userWithdrawHistory->id;
            $this->userWithdrawHistoryOptModel->claimant_id = $this->contll->partnerAdmin->id;
            $this->userWithdrawHistoryOptModel->claimant = $this->contll->partnerAdmin->name;
            $this->userWithdrawHistoryOptModel->claim_time = Carbon::now();
            $this->userWithdrawHistoryOptModel->audit_time = null;
            $this->userWithdrawHistoryOptModel->withdrawal_amount = $this->userWithdrawHistory->amount;
            $this->userWithdrawHistoryOptModel->status = $this->model::STATUS_CLAIMED;
            $this->userWithdrawHistoryOptModel->order_no = $this->userWithdrawHistory->order_id;
            if ($this->userWithdrawHistoryOptModel->save() && $this->userWithdrawHistory->save()) {
                $this->initVarEnv($inputDatas);
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    /**
     * 驳回
     * @param array $inputDatas
     * @return bool
     * @throws \Exception
     */
    private function turnDown($inputDatas) :bool
    {
        $this->takeOver($inputDatas);  //先认领
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_CLAIMED) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->audit_manager_id = $this->contll->partnerAdmin->id;
            $this->userWithdrawHistoryOpt->audit_manager = $this->contll->partnerAdmin->name;
            $this->userWithdrawHistoryOpt->audit_time = Carbon::now();
            $this->userWithdrawHistoryOpt->check_remark = $inputDatas['remark'];
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                // todo 用户提现的钱加回去,同时写入对应的账变记录
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * 审核通过
     * @param array $inputDatas
     * @return bool
     * @throws \Exception
     */
    private function passed($inputDatas) :bool
    {
        $this->takeOver($inputDatas); //先认领
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_CLAIMED) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->audit_manager_id = $this->contll->partnerAdmin->id;
            $this->userWithdrawHistoryOpt->audit_manager = $this->contll->partnerAdmin->name;
            $this->userWithdrawHistoryOpt->audit_time = Carbon::now();
            //$this->userWithdrawHistoryOpt->remittance_amount = '提现金额 - 手续费'; //待开发 统一出款API开发完在完善
            $this->userWithdrawHistoryOpt->channel_id = $inputDatas['channel_id'];
            // $this->userWithdrawHistoryOpt->channel_sign = $inputDatas['channel_id']; //待开发
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                // todo 调用统一出款API 出款成功则将订单状态变为成功 否则变为失败 如果是人工出款则特殊处理
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 手动成功
     * @param array $inputDatas
     * @return bool
     * @throws \Exception
     */
    private function toSuccess($inputDatas) :bool
    {
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_AUDIT_SUCCESS) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        }
        return false;
    }

    /**
     * 手动失败
     * @param array $inputDatas
     * @return bool
     * @throws \Exception
     */
    private function toFail($inputDatas) :bool
    {
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_AUDIT_SUCCESS) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                // todo 用户提现的钱加回去,同时写入对应的账变记录
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        }
        return false;
    }
}
