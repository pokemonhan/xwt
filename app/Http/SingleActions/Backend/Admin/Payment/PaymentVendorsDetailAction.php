<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentVendorsController;
use App\Models\Admin\Payment\BackendPaymentVendor;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentVendorsDetailAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentVendorsDetailAction
{
    /**
     * @var BackendPaymentVendor $model BackendPaymentVendor.
     */
    protected $model;

    /**
     * @param BackendPaymentVendor $BackendPaymentVendor BackendPaymentVendor.
     */
    public function __construct(BackendPaymentVendor $BackendPaymentVendor)
    {
        $this->model = $BackendPaymentVendor;
    }

    /**
     * 获取第三方厂商表
     * @param PaymentVendorsController $contll PaymentVendorsController.
     * @return JsonResponse
     */
    public function execute(PaymentVendorsController $contll): JsonResponse
    {
        $searchAbleFields = [];
        $orderFields = 'id';
        $orderFlow = 'asc';
        //处理执行第三方厂商表异常
        try {
            $banksDatas = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
            return $contll->msgOut(true, $banksDatas);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
