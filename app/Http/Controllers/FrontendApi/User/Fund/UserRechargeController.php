<?php

namespace App\Http\Controllers\FrontendApi\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\Frontend\User\Fund\UserRechargeListAction;
use Illuminate\Http\JsonResponse;
use App\Http\SingleActions\Frontend\User\Fund\UserRechargeAction;

class UserRechargeController extends FrontendApiMainController
{

    /**
     * 用户充值记录
     * @param  UserRechargeListAction $action
     * @return JsonResponse
     */
    public function rechargeList(UserRechargeListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 用户充值
     * @param  UserRechargeAction $action
     * @return JsonResponse
     */
    public function recharge(UserRechargeAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
