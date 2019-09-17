<?php

namespace App\Http\Controllers\MobileApi\Pay;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Pay\RechargeCallbackRequest;
use App\Http\SingleActions\Payment\PayRechargeAction;

class PayRechargeCallbackController extends Controller
{

    public function rechargeCallback(PayRechargeAction $action, RechargeCallbackRequest $request)
    {
        $action->rechageCallback($request);
    }
}
