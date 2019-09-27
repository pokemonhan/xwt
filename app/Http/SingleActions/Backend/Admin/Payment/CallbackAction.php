<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawStatusAction;
use App\Models\Pay\PaymentInfo;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersRechargeHistorie;
use App\Models\User\UsersWithdrawHistorie;
use App\Models\User\UsersWithdrawHistoryOpt;
use App\Pay\Core\PayHandlerFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CallbackAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class CallbackAction
{
    /**
     * @param integer $direction 金流方向.
     * @param string  $payment   支付方式.
     * @param array   $data      回调参数.
     * @return string
     * @throws \Exception 异常.
     */
    public function execute(int $direction, string $payment, array $data) :string
    {
        try {
            $verifyRes = (new PayHandlerFactory())->generatePayHandle($payment, ['payment_sign' => $payment])->verify($data);
            if (is_null($verifyRes['real_money']) || is_null($verifyRes['merchant_order_no'])) {
                throw new \Exception('real_money与merchant_order_no没有赋值');
            }
            if ($direction === PaymentInfo::DIRECTION_IN) { //入款
                $this->processingDeposits($verifyRes);
            } elseif ($direction === PaymentInfo::DIRECTION_OUT) { //出款
                $this->processingPayment($verifyRes);
            }
            return $verifyRes['back_param'];
        } catch (\Exception $e) {
            Log::channel('callback-exception')->info($e);
            // todo 给接入短信api 给开发人员发短信
            return 'system exception.';
        }
    }

    /**
     * 入款处理逻辑
     * @param array $verifyRes 参数.
     * @return void
     * @throws \Exception 异常.
     */
    private function processingDeposits(array $verifyRes):void
    {
        if ($verifyRes['flag']) {
            Log::channel('callback-log')->info('订单号：'.$verifyRes['merchant_order_no'].'验证签名成功.');
            $userRechargeHistoryModel = UsersRechargeHistorie::where('company_order_num', '=', $verifyRes['merchant_order_no']);
            $userRechargeHistory = $userRechargeHistoryModel->first();
            if ($verifyRes['real_money'] > $userRechargeHistory->amount) {
                $userRechargeHistory->where('status', UsersRechargeHistorie::STATUS_UNDERWAY)
                    ->update(['status' => UsersRechargeHistorie::STATUS_FAILURE]);
                Log::channel('callback-log')->info('订单号：'.$verifyRes['merchant_order_no'].'实际付款金额大于订单金额.');
            }
            DB::beginTransaction();
            $result = $userRechargeHistoryModel
                ->where('status', UsersRechargeHistorie::STATUS_UNDERWAY)
                ->update(['status' => UsersRechargeHistorie::STATUS_SUCCESS]);
            if ($result) {
                $params = [
                    'user_id' => $userRechargeHistory->user_id,
                    'amount' => $verifyRes['real_money'],
                ];
                $account = FrontendUsersAccount::where('user_id', $userRechargeHistory->user_id)->first();
                $resouce = $account->operateAccount($params, 'recharge');
                if ($resouce !== true) {
                    DB::rollBack();
                }
                Log::channel('callback-log')->info('订单号：' . $verifyRes['merchant_order_no'] . '充值成功.');
                DB::commit();
            }
        } else {
            // todo 订单状态变为失败
            UsersRechargeHistorie::where('company_order_num', $verifyRes['merchant_order_no'])
                ->where('status', UsersRechargeHistorie::STATUS_UNDERWAY)
                ->update(['status' => UsersRechargeHistorie::STATUS_FAILURE]);
            Log::channel('callback-log')->info('订单号：'.$verifyRes['merchant_order_no'].'验证签名失败.');
        }
    }

    /**
     * 出款处理逻辑
     * @param array $verifyRes 参数.
     * @return void
     */
    private function processingPayment(array $verifyRes) :void
    {
        $userWithdrawHistoryModel = UsersWithdrawHistorie::where('order_id', '=', $verifyRes['merchant_order_no']);
        $userWithdrawHistory = $userWithdrawHistoryModel->first();
        if ($verifyRes['flag']) {
            (new WithdrawStatusAction(new UsersWithdrawHistorie(), new UsersWithdrawHistoryOpt()))->execute((new WithdrawController()), ['id'=>$userWithdrawHistory->id,'status'=>UsersWithdrawHistorie::STATUS_SUCCESS]);
            Log::channel('callback-log')->info('订单号：'.$verifyRes['merchant_order_no'].'验证签名成功.');
        } else {
            Log::channel('callback-log')->info('订单号：'.$verifyRes['merchant_order_no'].'验证签名失败.');
        }
    }
}
