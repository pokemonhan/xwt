<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentInfosController;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentInfosDetailAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentInfosDetailAction
{
    /**
     * @var PaymentInfo $model PaymentInfo.
     */
    protected $model;

    /**
     * PaymentInfosDetailAction constructor.
     * @param PaymentInfo $paymentInfo PaymentInfo.
     */
    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->model = $paymentInfo;
    }

    /**
     * 支付方式详情表
     * @param PaymentInfosController $contll 支付方式详情表.
     * @return JsonResponse
     */
    public function execute(PaymentInfosController $contll): JsonResponse
    {
        //处理执行支付方式详情表异常
        try {
            $field = 'sort';
            $type = 'asc';
            $searchAbleFields = ['front_name'];
            $datas = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $field, $type);
            //拼接支付方式的返回地址
            foreach ($datas as $value) {
                $value['back_url'] = rtrim(configure('back_url'), '/'). '/' . ltrim($value['payment_sign'], '/');
            }
            return $contll->msgOut(true, $datas);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
