<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentInfosController;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

/**
 * Class PaymentInfosUpdateStatusAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentInfosUpdateStatusAction
{
    use BaseCache;

    /**
     * @var PaymentInfo $model 支付方式详情表模型.
     */
    protected $model;

    /**
     * PaymentInfosUpdateStatusAction constructor.
     * @param PaymentInfo $paymentInfo 支付方式详情表模型.
     */
    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->model = $paymentInfo;
    }

    /**
     * 更新状态信息
     * @param PaymentInfosController $contll     主控制器.
     * @param array                  $inputDatas 前端获取编辑信息.
     * @return JsonResponse
     */
    public function execute(PaymentInfosController $contll, array $inputDatas): JsonResponse
    {
        //更新状态信息
        try {
            $payment_info = PaymentInfo::where('id', $inputDatas['id'])->first();
            $payment_info->fill($inputDatas);
            $payment_info->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
