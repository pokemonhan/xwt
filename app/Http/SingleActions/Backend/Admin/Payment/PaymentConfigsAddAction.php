<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentConfigsController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\BackendPaymentType;
use App\Models\Admin\Payment\BackendPaymentVendor;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

/**
 * Class PaymentConfigsAddAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentConfigsAddAction
{
    use BaseCache;

    /**
     * @var BackendPaymentConfig $model 支付配置信息模型.
     */
    protected $model;

    /**
     * PaymentConfigsAddAction constructor.
     * @param BackendPaymentConfig $backendPaymentConfig 支付配置信息模型.
     */
    public function __construct(BackendPaymentConfig $backendPaymentConfig)
    {
        $this->model = $backendPaymentConfig;
    }

    /**
     * 执行添加操作
     * @param PaymentConfigsController $contll     主控制器.
     * @param array                    $inputDatas 前端获取添加参数.
     * @return JsonResponse
     */
    public function execute(PaymentConfigsController $contll, array $inputDatas): JsonResponse
    {
        try {
            //获取需要的参数
            $payment_vendor = BackendPaymentVendor::find($inputDatas['payment_vendor_id']);
            $payment_type = BackendPaymentType::find($inputDatas['payment_type_id']);
            $addDatas = [];
            $addDatas['payment_vendor_name'] = $payment_vendor->payment_vendor_name;
            $addDatas['payment_vendor_sign'] = $payment_vendor->payment_vendor_sign;
            $addDatas['payment_type_name'] = $payment_type->payment_type_name;
            $addDatas['payment_type_sign'] = $payment_type->payment_type_sign;
            $addDatas['banks_code'] = [
                'name' => $payment_type->payment_type_name,
                'ico' => $payment_type->payment_ico,
                'code' => $payment_vendor->payment_vendor_name,
            ];
            $addDatas['banks_code'] = json_encode($addDatas['banks_code']);
            $inputDatas = array_merge($inputDatas, $addDatas);
            //执行添加操作
            $configure = new $this->model();
            $configure->fill($inputDatas);
            $configure->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
