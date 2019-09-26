<?php

namespace App\Http\SingleActions\Backend\Admin\Withdraw;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Models\Pay\PaymentInfo;
use App\Models\User\UsersWithdrawHistorie;
use Illuminate\Http\JsonResponse;

/**
 * Class WithdrawChannelAction
 * @package App\Http\SingleActions\Backend\Admin\Withdraw
 */
class WithdrawChannelAction
{
    /**
     * @param WithdrawController $contll     控制器.
     * @param array              $inputDatas 数据.
     * @return JsonResponse
     */
    public function execute(WithdrawController $contll, array $inputDatas) :JsonResponse
    {
        $userWithdrawHistory = UsersWithdrawHistorie::where('id', $inputDatas['id'])->first();
        $output = PaymentInfo::getPaymentInfoLists(PaymentInfo::DIRECTION_OUT);
        foreach ($output as $key1 => $value1) {
            if (!in_array(strtolower($userWithdrawHistory->bank_sign), array_column($value1->banks_code, 'payment_type_sign'))) {
                unset($output[$key1]);
            }
            if ($userWithdrawHistory->amount < $value1->min || $userWithdrawHistory->amount > $value1->max) {
                unset($output[$key1]);
            }
        }
        return $contll->msgOut(true, $output);
    }
}
