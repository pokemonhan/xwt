<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentInfosController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

/**
 * Class PaymentInfosAddAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentInfosAddAction
{
    use BaseCache;

    /**
     * @var PaymentInfo $model PaymentInfo.
     */
    protected $model;

    /**
     * @param PaymentInfo $paymentInfo PaymentInfo.
     */
    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->model = $paymentInfo;
    }

    /**
     * 执行添加操作
     * @param PaymentInfosController $contll     执行添加操作.
     * @param array                  $inputDatas InputDatas.
     * @return JsonResponse
     */
    public function execute(PaymentInfosController $contll, array $inputDatas): JsonResponse
    {
        //获取支付方式配置信息
        $payment_config = BackendPaymentConfig::where('id', $inputDatas['config_id'])->first();
        //判断是否存在获取支付方式配置信息
        if (!$payment_config) {
            return $contll->msgOut(false, [], '102601');
        }
        $inputDatas['direction'] = $payment_config->direction;//金流的方向 1 入款 0 出款
        $inputDatas['payment_name'] = $payment_config->payment_name;//支付方式名称
        $inputDatas['payment_sign'] = $payment_config->payment_sign;//支付方式标记
        $inputDatas['payment_vendor_sign'] = $payment_config->payment_vendor_sign;//支付方式厂商标记
        $inputDatas['payment_type_sign'] = $payment_config->payment_type_sign;//支付方式种类标记
        $inputDatas['request_url'] = $payment_config->request_url;//支付方式请求地址
        $inputDatas['back_url'] = rtrim(configure('back_url'), '/'). '/' .$inputDatas['direction'].'/'. ltrim($inputDatas['payment_sign'], '/');//支付方式的返回地址
        //处理执行添加支付方式详情表异常
        try {
            $configure = new $this->model();
            $configure->fill($inputDatas);
            $configure->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
