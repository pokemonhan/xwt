<?php

namespace App\Http\Controllers\BackendApi\Admin\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Payment\PaymentTypesAddRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentTypesDeleteRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentTypesEditRequest;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentTypesAddAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentTypesDeleteAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentTypesDetailAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentTypesEditAction;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentTypesController
 * @package App\Http\Controllers\BackendApi\Admin\Payment
 */
class PaymentTypesController extends BackEndApiMainController
{

    /**
     * 支付方式类型信息
     * @param PaymentTypesDetailAction $action PaymentTypesDetailAction.
     * @return JsonResponse
     */
    public function detail(PaymentTypesDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 删除支付方式类型信息
     * @param PaymentTypesDeleteRequest $request PaymentTypesDeleteRequest.
     * @param PaymentTypesDeleteAction  $action  PaymentTypesDeleteAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function delete(PaymentTypesDeleteRequest $request, PaymentTypesDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加支付方式类型信息
     * @param PaymentTypesAddRequest $request PaymentTypesAddRequest.
     * @param PaymentTypesAddAction  $action  PaymentTypesAddAction.
     * @return JsonResponse
     */
    public function doadd(PaymentTypesAddRequest $request, PaymentTypesAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑支付方式类型信息
     * @param PaymentTypesEditRequest $request PaymentTypesEditRequest.
     * @param PaymentTypesEditAction  $action  PaymentTypesEditAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function edit(PaymentTypesEditRequest $request, PaymentTypesEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
