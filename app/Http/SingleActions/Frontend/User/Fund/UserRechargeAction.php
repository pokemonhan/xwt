<?php

namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\User\Fund\UserRechargeRequest;
use App\Lib\Help;
use App\Models\Finance\UserRecharge;
use Illuminate\Http\JsonResponse;

class UserRechargeAction
{
    protected $model;

    /**
     * UserRechargeAction constructor.
     * @param UserRecharge $usersRecharge
     */
    public function __construct(UserRecharge $usersRecharge)
    {
        $this->model = $usersRecharge;
    }

    /**
     * 用户充值列表
     * @param  UserRechargeRequest  $userRechargeRequest
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(UserRechargeRequest $userRechargeRequest, FrontendApiMainController $contll): JsonResponse
    {
        // 1. 如果没有设置资金密码 跳转到密码设定页面
        if (empty($contll->partnerUser->fund_password)) {
            return $contll->msgOut(0, '对不起, 资金密码未设置!');
        }

        if ($contll->partnerUser->is_tester) {
            return $contll->msgOut(0, '对不起, 测试账户不能充值!');
        }

        $amount = request('amount', 0);
        $channel = request('channel', 0);
        $bankSign = request('bank_sign', '');

        // 6. 检查金额输入 输入为空 或者 取整后和原值不同
        if (empty($amount) || $amount != intval($amount)) {
            return $contll->msgOut(0, '对不起, 无效的充值金额!');
        }

        $_amount = intval($amount); // 前台传的是元

        // 发起充值
        $res = $contll->partnerUser->recharge($_amount, $channel, $bankSign, 'ios');
        if (!is_array($res)) {
            return $contll->msgOut(0, $res);
        }

        return $contll->msgOut(true, []);
    }
}
