<?php

namespace App\Http\Controllers\MobileApi\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\Frontend\User\Fund\UserChangeTypeList;
use App\Http\SingleActions\Frontend\User\Fund\UserFundAction;
use App\Http\SingleActions\Frontend\User\Fund\UserRechargeListAction;
use Illuminate\Http\JsonResponse;

class UserFundController extends FrontendApiMainController
{
    /**
     * 用户账变记录
     * @param  UserFundAction $action
     * @return JsonResponse
     */
    public function lists(UserFundAction $action): JsonResponse
    {
        return $action->execute($this);
    }

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
     * 帐变类型列表
     * @param   UserChangeTypeList $action
     * @return  JsonResponse
     */
    public function changeTypeList(UserChangeTypeList $action): JsonResponse
    {
        return $action->execute($this);
    }
}
