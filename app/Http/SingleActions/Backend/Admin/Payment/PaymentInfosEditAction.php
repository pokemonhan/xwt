<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentInfosController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

/**
 * Class PaymentInfosEditAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentInfosEditAction
{
    use BaseCache;

    /**
     * @var PaymentInfo $model 支付方式详情表模型.
     */
    protected $model;

    /**
     * PaymentInfosEditAction constructor.
     * @param PaymentInfo $paymentInfo 支付方式详情表模型.
     */
    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->model = $paymentInfo;
    }

    /**
     * 编辑支付方式详情表
     * @param PaymentInfosController $contll     主控制器.
     * @param array                  $inputDatas 前端获取编辑信息.
     * @return JsonResponse
     */
    public function execute(PaymentInfosController $contll, array $inputDatas): JsonResponse
    {
        //获取支付方式配置信息参数
        $payment_config = BackendPaymentConfig::where('id', $inputDatas['config_id'])->first();
        $inputDatas['direction'] = $payment_config->direction;//金流的方向 1 入款 0 出款
        $inputDatas['payment_name'] = $payment_config->payment_name;//支付方式名称
        $inputDatas['payment_sign'] = $payment_config->payment_sign;//支付方式标记
        $inputDatas['payment_vendor_sign'] = $payment_config->payment_vendor_sign;//支付方式厂商标记
        $inputDatas['payment_type_sign'] = $payment_config->payment_type_sign;//支付方式种类标记
        $inputDatas['request_url'] = $payment_config->request_url;//支付方式请求地址
        $inputDatas['back_url'] = rtrim(configure('back_url'), '/'). '/' . ltrim($inputDatas['payment_sign'], '/');//支付方式的返回地址
        //处理执行编辑支付方式详情表异常
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
