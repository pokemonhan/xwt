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
 * 支付方式类型
 * Class PaymentTypesController
 * @package App\Http\Controllers\BackendApi\Admin\Payment
 */
class PaymentTypesController extends BackEndApiMainController
{

    /**
     * 获取支付方式类型信息列表
     * @param PaymentTypesDetailAction $action 逻辑处理.
     * @return JsonResponse
     */
    public function detail(PaymentTypesDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 删除支付方式类型信息
     * @param PaymentTypesDeleteRequest $request 验证器.
     * @param PaymentTypesDeleteAction  $action  逻辑处理.
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
     * @param PaymentTypesAddRequest $request 验证器.
     * @param PaymentTypesAddAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function doadd(PaymentTypesAddRequest $request, PaymentTypesAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑支付方式类型信息
     * @param PaymentTypesEditRequest $request 验证器.
     * @param PaymentTypesEditAction  $action  逻辑处理.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function edit(PaymentTypesEditRequest $request, PaymentTypesEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
