<?php

namespace App\Http\Controllers\MobileApi\Pay;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;

use App\Http\Requests\Frontend\Pay\RechargeList;
use App\Http\Requests\Frontend\Pay\RechargeRequest;
use App\Http\Requests\Frontend\Pay\WithdrawRequest;
use Illuminate\Http\JsonResponse;
use App\Http\SingleActions\Payment\PayRechargeAction;
use App\Http\SingleActions\Payment\PayWithdrawAction;

class PayController extends FrontendApiMainController
{
    /**
     * 获取充值渠道
     * @param PayRechargeAction $action
     * @return JsonResponse
     */
    public function getRechargeChannel(PayRechargeAction $action) : JsonResponse
    {
        return $action->getRechargeChannel($this) ;
    }

    /**
     * 发起充值
     * @param PayRechargeAction $action
     * @param RechargeRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function recharge(PayRechargeAction $action, RechargeRequest $request) : JsonResponse
    {
        return $action->dorRecharge($this, $request) ;
    }

    /**
     * 发起提现
     * @param PayWithdrawAction $action
     * @param WithdrawRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function withdraw(PayWithdrawAction $action, WithdrawRequest $request) : JsonResponse
    {
        return $action->applyWithdraw($this, $request) ;
    }


    /**
     * 用户充值申请列表
     * @param PayRechargeAction $action
     * @param RechargeList $request
     * @return JsonResponse
     */
    public function rechargeList(PayRechargeAction $action, RechargeList $request): JsonResponse
    {
        return $action->rechargeList($this, $request);
    }

    public function realRechargeList(PayRechargeAction $action): JsonResponse
    {
        return $action->realRechargeList($this);
    }

    /**
     * 用户提现申请列表
     * @param PayWithdrawAction $action
     * @param RechargeList $request
     * @return JsonResponse
     */
    public function withdrawList(PayWithdrawAction $action, RechargeList $request): JsonResponse
    {
        return $action->withdrawList($this, $request);
    }

    public function realWithdrawList(PayWithdrawAction $action, RechargeList $request): JsonResponse
    {
        return $action->realWithdrawList($this, $request);
    }
}
