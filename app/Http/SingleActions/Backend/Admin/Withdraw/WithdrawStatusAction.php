<?php


namespace App\Http\SingleActions\Backend\Admin\Withdraw;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Models\Pay\PaymentInfo;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersWithdrawHistorie;
use App\Models\User\UsersWithdrawHistoryOpt;
use App\Pay\Core\PayHandlerFactory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class WithdrawStatusAction
 * @package App\Http\SingleActions\Backend\Admin\Withdraw
 */
class WithdrawStatusAction
{
    /**
     * @var UsersWithdrawHistorie $model 模型.
     */
    protected $model;
    /**
     * @var UsersWithdrawHistoryOpt $userWithdrawHistoryOptModel 提现记录操作的模型.
     */
    protected $userWithdrawHistoryOptModel;
    /**
     * @var object $userWithdrawHistory 用户提现记录.
     */
    protected $userWithdrawHistory;
    /**
     * @var object $userWithdrawHistoryOpt 用户提现记录对应的操作.
     */
    protected $userWithdrawHistoryOpt;
    /**
     * @var object $contll 控制器.
     */
    protected $contll;

    /**
     * WithdrawStatusAction constructor.
     * @param UsersWithdrawHistorie   $usersWithdrawHistorie   用户提现记录.
     * @param UsersWithdrawHistoryOpt $usersWithdrawHistoryOpt 用户提现记录对应的操作.
     */
    public function __construct(UsersWithdrawHistorie $usersWithdrawHistorie, UsersWithdrawHistoryOpt $usersWithdrawHistoryOpt)
    {
        $this->model = $usersWithdrawHistorie;
        $this->userWithdrawHistoryOptModel = $usersWithdrawHistoryOpt;
    }

    /**
     * 初始化
     * @param array $inputDatas 参数.
     * @return void
     */
    public function initVarEnv(array $inputDatas)
    {
        $this->userWithdrawHistory = $this->model::where('id', $inputDatas['id'])->first(); //提现记录
        $this->userWithdrawHistoryOpt = $this->userWithdrawHistory->withdrawHistoryOpt;
    }

    /**
     * @param WithdrawController $contll     控制器.
     * @param array              $inputDatas 参数.
     * @return JsonResponse
     */
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
            Log::channel('withdrawstatus-log')->info($e);
            return $contll->msgOut(false, [], '400', '系统错误');
        }
    }

    /**
     * 接手处理
     * @param array $inputDatas 参数.
     * @return boolean
     * @throws \Exception 异常.
     */
    private function takeOver(array $inputDatas) :bool
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
     * @param array $inputDatas 参数.
     * @return boolean
     * @throws \Exception 异常.
     */
    private function turnDown(array $inputDatas) :bool
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
                $params = [
                    'user_id' => $this->userWithdrawHistory->user_id,
                    'amount' => $this->userWithdrawHistory->amount,
                ];
                $account = FrontendUsersAccount::where('user_id', $this->userWithdrawHistory->user_id)->first();
                $resouce = $account->operateAccount($params, 'withdraw_un_frozen');
                if ($resouce !== true) {
                    DB::rollBack();
                    return false;
                }
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
     * @param array $inputDatas 参数.
     * @return boolean
     * @throws \Exception 异常.
     */
    private function passed(array $inputDatas) :bool
    {
        $this->takeOver($inputDatas); //先认领
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_CLAIMED) {
            DB::beginTransaction();
            $paymentInfo = PaymentInfo::where('payment_sign', $inputDatas['channel'])->first();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->audit_manager_id = $this->contll->partnerAdmin->id;
            $this->userWithdrawHistoryOpt->audit_manager = $this->contll->partnerAdmin->name;
            $this->userWithdrawHistoryOpt->audit_time = Carbon::now();
            if (!empty($paymentInfo->rebate_handFee)) {
                $this->userWithdrawHistoryOpt->remittance_amount = $this->userWithdrawHistory->amount - $paymentInfo->rebate_handFee;
                $this->userWithdrawHistory->real_amount = $this->userWithdrawHistory->amount - $paymentInfo->rebate_handFee;
            } else {
                $this->userWithdrawHistoryOpt->remittance_amount = $this->userWithdrawHistory->amount;
                $this->userWithdrawHistory->real_amount = $this->userWithdrawHistory->amount;
            }
            $this->userWithdrawHistoryOpt->channel_id = $paymentInfo->id;
            $this->userWithdrawHistoryOpt->channel_sign = $inputDatas['channel'];
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                $this->withdraw($paymentInfo, $inputDatas); //去提现
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
     * @param PaymentInfo $paymentInfo 支付渠道.
     * @param array       $inputDatas  数据.
     * @return void
     */
    public function withdraw(PaymentInfo $paymentInfo, array $inputDatas) :void
    {
        if (!empty($banks_code = $paymentInfo->paymentConfig->banks_code)) { //获得支付厂商的bank_code
            $banks_code = explode('|', $banks_code);
            foreach ($banks_code as $item) {
                [$bank_code1,$bank_code2] = explode('=', $item);
                if (strtolower($bank_code1) === strtolower($this->userWithdrawHistory->bank_sign)) {
                    $payment_bank_code = $bank_code2;
                }
            }
        }
        $payParams = [
            'payment_sign' => $inputDatas['channel'],
            'order_no' => $this->userWithdrawHistory->order_id,
            'money' => $this->userWithdrawHistory->real_amount,
            'bank_code' => $payment_bank_code??'',
            'card_number' => $this->userWithdrawHistory->card_number??'',
            'card_username' => $this->userWithdrawHistory->card_username??'',
        ];
        (new PayHandlerFactory())->generatePayHandle($inputDatas['channel'], $payParams)->handle();
    }
    /**
     * 手动成功
     * @param array $inputDatas 参数.
     * @return boolean
     * @throws \Exception 异常.
     */
    private function toSuccess(array $inputDatas) :bool
    {
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_AUDIT_SUCCESS) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                $params = [
                    'user_id' => $this->userWithdrawHistory->user_id,
                    'amount' => $this->userWithdrawHistory->amount,
                ];
                $account = FrontendUsersAccount::where('user_id', $this->userWithdrawHistory->user_id)->first();
                $resouce = $account->operateAccount($params, 'withdraw_finish');
                if ($resouce !== true) {
                    DB::rollBack();
                    return false;
                }
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
     * @param array $inputDatas 参数.
     * @return boolean
     * @throws \Exception 异常.
     */
    private function toFail(array $inputDatas) :bool
    {
        if ((int) $this->userWithdrawHistory->status === $this->model::STATUS_AUDIT_SUCCESS) {
            DB::beginTransaction();
            $this->userWithdrawHistory->status = $inputDatas['status'];
            $this->userWithdrawHistoryOpt->status = $inputDatas['status'];
            if ($this->userWithdrawHistory->save() && $this->userWithdrawHistoryOpt->save()) {
                DB::commit();
                $params = [
                    'user_id' => $this->userWithdrawHistory->user_id,
                    'amount' => $this->userWithdrawHistory->amount,
                ];
                $account = FrontendUsersAccount::where('user_id', $this->userWithdrawHistory->user_id)->first();
                $resouce = $account->operateAccount($params, 'withdraw_un_frozen');
                if ($resouce !== true) {
                    DB::rollBack();
                    return false;
                }
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        }
        return false;
    }
}
