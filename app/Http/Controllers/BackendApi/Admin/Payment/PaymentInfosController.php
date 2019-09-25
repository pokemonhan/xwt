<?php

namespace App\Http\Controllers\BackendApi\Admin\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Payment\PaymentInfosAddRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentInfosDeleteRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentInfosDetailRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentInfosSortRequest;
use App\Http\Requests\Backend\Admin\Payment\PaymentInfosEditRequest;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentInfosAddAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentInfosDeleteAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentInfosDetailAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentInfosEditAction;
use App\Http\SingleActions\Backend\Admin\Payment\PaymentInfosSortAction;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentInfosController
 * @package App\Http\Controllers\BackendApi\Admin\Payment
 */
class PaymentInfosController extends BackEndApiMainController
{

    /**
     * 支付方式详情表
     * @param PaymentInfosDetailRequest $request PaymentInfosDetailRequest.
     * @param PaymentInfosDetailAction  $action  PaymentInfosDetailAction.
     * @return JsonResponse
     */
    public function detail(PaymentInfosDetailRequest $request, PaymentInfosDetailAction $action): JsonResponse
    {
        $request->validated();
        return $action->execute($this);
    }

    /**
     * 删除支付方式信息
     * @param PaymentInfosDeleteRequest $request PaymentInfosDeleteRequest.
     * @param PaymentInfosDeleteAction  $action  PaymentInfosDeleteAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function delete(PaymentInfosDeleteRequest $request, PaymentInfosDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加支付方式详情表
     * @param PaymentInfosAddRequest $request PaymentInfosAddRequest.
     * @param PaymentInfosAddAction  $action  PaymentInfosAddAction.
     * @return JsonResponse
     */
    public function doadd(PaymentInfosAddRequest $request, PaymentInfosAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑支付方式详情表
     * @param PaymentInfosEditRequest $request PaymentInfosEditRequest.
     * @param PaymentInfosEditAction  $action  PaymentInfosEditAction.
     * @return JsonResponse
     */
    public function edit(PaymentInfosEditRequest $request, PaymentInfosEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 支付方式详情表排序
     * @param PaymentInfosSortRequest $request PaymentInfosSortRequest.
     * @param PaymentInfosSortAction  $action  PaymentInfosSortAction.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function sort(PaymentInfosSortRequest $request, PaymentInfosSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
