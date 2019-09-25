<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentTypesController;
use App\Models\Admin\Payment\BackendPaymentType;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentTypesDetailAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentTypesDetailAction
{
    /**
     * @var BackendPaymentType $model BackendPaymentType.
     */
    protected $model;

    /**
     * @param BackendPaymentType $BackendPaymentType BackendPaymentType.
     */
    public function __construct(BackendPaymentType $BackendPaymentType)
    {
        $this->model = $BackendPaymentType;
    }

    /**
     * 支付方式类型表表
     * @param PaymentTypesController $contll PaymentTypesController.
     * @return JsonResponse
     */
    public function execute(PaymentTypesController $contll): JsonResponse
    {
        $searchAbleFields = [];
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
