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
     * @var BackendPaymentVendor $model 第三方厂商表模型.
     */
    protected $model;

    /**
     * @param BackendPaymentVendor $BackendPaymentVendor 第三方厂商表模型.
     */
    public function __construct(BackendPaymentVendor $BackendPaymentVendor)
    {
        $this->model = $BackendPaymentVendor;
    }

    /**
     * 获取第三方厂商表
     * @param PaymentVendorsController $contll 主控制器.
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
