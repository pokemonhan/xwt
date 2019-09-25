<?php

namespace App\Http\Controllers\BackendApi\Admin\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Payment\PaymentConfigsAddRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentConfigsDeleteRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentConfigsEditRequest;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentConfigsAddAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentConfigsDeleteAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentConfigsDetailAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentConfigsEditAction;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentConfigsController
 * @package App\Http\Controllers\BackendApi\Admin\Payment
 */
class PaymentConfigsController extends BackEndApiMainController
{
    /**
     * 支付配置信息
     * @param PaymentConfigsDetailAction $action PaymentConfigsDetailAction.
     * @return JsonResponse
     */
    public function detail(PaymentConfigsDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 删除支付配置信息
     * @param PaymentConfigsDeleteRequest $request PaymentConfigsDeleteRequest.
     * @param PaymentConfigsDeleteAction  $action  PaymentConfigsDeleteAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function delete(PaymentConfigsDeleteRequest $request, PaymentConfigsDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加支付配置信息
     * @param PaymentConfigsAddRequest $request PaymentConfigsAddRequest.
     * @param PaymentConfigsAddAction  $action  PaymentConfigsAddAction.
     * @return JsonResponse
     */
    public function doadd(PaymentConfigsAddRequest $request, PaymentConfigsAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑支付配置信息
     * @param PaymentConfigsEditRequest $request PaymentConfigsEditRequest.
     * @param PaymentConfigsEditAction  $action  PaymentConfigsEditAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function edit(PaymentConfigsEditRequest $request, PaymentConfigsEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
