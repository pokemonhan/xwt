<?php

namespace App\Http\Controllers\BackendApi\Admin\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Payment\PaymentVendorsAddRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentVendorsDeleteRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentVendorsEditRequest;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentVendorsAddAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentVendorsDeleteAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentVendorsDetailAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentVendorsEditAction;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentVendorsController
 * @package App\Http\Controllers\BackendApi\Admin\Payment
 */
class PaymentVendorsController extends BackEndApiMainController
{

    /**
     * 第三方厂商信息
     * @param PaymentVendorsDetailAction $action PaymentVendorsDetailAction.
     * @return JsonResponse
     */
    public function detail(PaymentVendorsDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 删除第三方厂商信息
     * @param PaymentVendorsDeleteRequest $request PaymentVendorsDeleteRequest.
     * @param PaymentVendorsDeleteAction  $action  PaymentVendorsDeleteAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function delete(PaymentVendorsDeleteRequest $request, PaymentVendorsDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加第三方厂商信息
     * @param PaymentVendorsAddRequest $request PaymentVendorsAddRequest.
     * @param PaymentVendorsAddAction  $action  PaymentVendorsAddAction.
     * @return JsonResponse
     */
    public function doadd(PaymentVendorsAddRequest $request, PaymentVendorsAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑第三方厂商信息
     * @param PaymentVendorsEditRequest $request PaymentVendorsEditRequest.
     * @param PaymentVendorsEditAction  $action  PaymentVendorsEditAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function edit(PaymentVendorsEditRequest $request, PaymentVendorsEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
