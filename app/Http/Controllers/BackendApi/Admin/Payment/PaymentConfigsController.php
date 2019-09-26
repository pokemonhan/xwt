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
 * 支付配置
 * Class PaymentConfigsController
 * @package App\Http\Controllers\BackendApi\Admin\Payment
 */
class PaymentConfigsController extends BackEndApiMainController
{
    /**
     * 获取支付配置信息列表
     * @param PaymentConfigsDetailAction $action 逻辑处理.
     * @return JsonResponse
     */
    public function detail(PaymentConfigsDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 删除支付配置信息
     * @param PaymentConfigsDeleteRequest $request 验证器.
     * @param PaymentConfigsDeleteAction  $action  逻辑处理.
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
     * @param PaymentConfigsAddRequest $request 验证器.
     * @param PaymentConfigsAddAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function doadd(PaymentConfigsAddRequest $request, PaymentConfigsAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑支付配置信息
     * @param PaymentConfigsEditRequest $request 验证器.
     * @param PaymentConfigsEditAction  $action  逻辑处理.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function edit(PaymentConfigsEditRequest $request, PaymentConfigsEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
