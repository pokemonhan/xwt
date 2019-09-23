<?php

namespace App\Http\Controllers\FrontendApi\Pay;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;

use App\Http\Requests\Frontend\Pay\RechargeList;
use App\Http\Requests\Frontend\Pay\RechargeRequest;
use App\Http\Requests\Frontend\Pay\WithdrawRequest;
use Illuminate\Http\JsonResponse;
use App\Http\SingleActions\Payment\PayRechargeAction;
use App\Http\SingleActions\Payment\PayWithdrawAction;

/**
 * Class PayController
 * @package App\Http\Controllers\FrontendApi\Pay
 */
class PayController extends FrontendApiMainController
{
    /**
     * 获取充值渠道
     * @param PayRechargeAction $action 逻辑处理.
     * @return JsonResponse
     */
    public function getRechargeChannel(PayRechargeAction $action) : JsonResponse
    {
        return $action->getRechargeChannel($this) ;
    }

    /**
     * 获取充值渠道 v2.0
     * @param PayRechargeAction $action 逻辑处理.
     * @return JsonResponse
     */
    public function getRechargeChannelNew(PayRechargeAction $action) :JsonResponse
    {
        return $action->getRechargeChannelNew($this);
    }

    /**
     * 发起充值
     * @param PayRechargeAction $action  逻辑处理.
     * @param RechargeRequest   $request 验证器.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function recharge(PayRechargeAction $action, RechargeRequest $request) : JsonResponse
    {
        return $action->dorRecharge($this, $request) ;
    }

    /**
     * 发起充值新版
     * @param PayRechargeAction $action  逻辑处理.
     * @param RechargeRequest   $request 验证器.
     * @return mixed
     */
    public function rechargeNew(PayRechargeAction $action, RechargeRequest $request)
    {
        $inputDatas = $request->validated();
        return $action->recharge($this, $inputDatas);
    }
    /**
     * 发起提现
     * @param PayWithdrawAction $action  逻辑处理.
     * @param WithdrawRequest   $request 验证器.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function withdraw(PayWithdrawAction $action, WithdrawRequest $request) : JsonResponse
    {
        return $action->applyWithdraw($this, $request) ;
    }


    /**
     * 用户充值申请列表
     * @param PayRechargeAction $action  逻辑处理.
     * @param RechargeList      $request 验证器.
     * @return JsonResponse
     */
    public function rechargeList(PayRechargeAction $action, RechargeList $request): JsonResponse
    {
        return $action->rechargeList($this, $request);
    }

    /**
     * @param PayRechargeAction $action 逻辑处理.
     * @return JsonResponse
     */
    public function realRechargeList(PayRechargeAction $action): JsonResponse
    {
        return $action->realRechargeList($this);
    }

    /**
     * 用户提现申请列表
     * @param PayWithdrawAction $action  逻辑处理.
     * @param RechargeList      $request 验证器.
     * @return JsonResponse
     */
    public function withdrawList(PayWithdrawAction $action, RechargeList $request): JsonResponse
    {
        return $action->withdrawList($this, $request);
    }

    /**
     * @param PayWithdrawAction $action  逻辑处理.
     * @param RechargeList      $request 验证器.
     * @return JsonResponse
     */
    public function realWithdrawList(PayWithdrawAction $action, RechargeList $request): JsonResponse
    {
        return $action->realWithdrawList($this, $request);
    }
}
