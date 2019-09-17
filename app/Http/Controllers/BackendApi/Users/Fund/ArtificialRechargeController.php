<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\ArtificialRechargeRechargeRequest;
use App\Http\SingleActions\Backend\Users\Fund\ArtificialRechargeRechargeAction;
use Illuminate\Http\JsonResponse;

class ArtificialRechargeController extends BackEndApiMainController
{
    /**
     * 给用户人工充值
     * @param  ArtificialRechargeRechargeRequest $request
     * @param  ArtificialRechargeRechargeAction  $action
     * @return JsonResponse
     */
    public function recharge(
        ArtificialRechargeRechargeRequest $request,
        ArtificialRechargeRechargeAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
