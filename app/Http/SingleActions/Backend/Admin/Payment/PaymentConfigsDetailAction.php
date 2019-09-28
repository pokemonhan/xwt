<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentConfigsController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentConfigsDetailAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentConfigsDetailAction
{
    /**
     * @var BackendPaymentConfig $model 支付方式详情表模型.
     */
    protected $model;

    /**
     * @param BackendPaymentConfig $backendPaymentConfig 支付方式详情表模型.
     */
    public function __construct(BackendPaymentConfig $backendPaymentConfig)
    {
        $this->model = $backendPaymentConfig;
    }

    /**
     * 支付方式详情表
     * @param PaymentConfigsController $contll 前端主控制器obj.
     * @return JsonResponse
     */
    public function execute(PaymentConfigsController $contll): JsonResponse
    {
        $searchAbleFields = ['id'];
        $orderFields = 'id';
        $orderFlow = 'asc';
        //处理执行支付方式配置表异常
        try {
            $banksDatas = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
            return $contll->msgOut(true, $banksDatas);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
