<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentVendorsController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\BackendPaymentVendor;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentVendorsEditAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentVendorsEditAction
{
    use BaseCache;

    /**
     * @var BackendPaymentVendor $model BackendPaymentVendor.
     */
    protected $model;

    /**
     * PaymentVendorsEditAction constructor
     * @param BackendPaymentVendor $backendPaymentVendor BackendPaymentVendor.
     */
    public function __construct(BackendPaymentVendor $backendPaymentVendor)
    {
        $this->model = $backendPaymentVendor;
    }

    /**
     * 编辑第三方厂商表
     * @param PaymentVendorsController $contll     PaymentVendorsController.
     * @param array                    $inputDatas InputDatas.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentVendorsController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //判断修改第三方厂商表的数据是否存在
            if (!empty($inputDatas['payment_vendor_name'] && $inputDatas['payment_vendor_sign'])) {
                $payment_vendor = BackendPaymentVendor::where([['payment_vendor_name', '=', $inputDatas['payment_vendor_name']], ['payment_vendor_sign', '=', $inputDatas['payment_vendor_sign']]])->get();
                if (count($payment_vendor) > 0) {
                    return $contll->msgOut(false, [], '102800');
                }
                //处理拼接数据
                $inputDatas['whitelist_ips'] = $inputDatas['whitelist_ips'] . '|';
                $inputDatas['whitelist_ips'] = trim($inputDatas['whitelist_ips'], '|');
            }
            //执行添加第三方厂商表操作
            $payment_vendor = BackendPaymentVendor::where('id', $inputDatas['id'])->first();
            $payment_vendor->fill($inputDatas);
            $payment_vendor->save();
            //更新支付配置表信息
            $editDatas = [];
            $editDatas['payment_vendor_name'] = $payment_vendor->payment_type_name;
            $editDatas['payment_vendor_sign'] = $payment_vendor->payment_type_sign;
            BackendPaymentConfig::where('payment_type_id', $inputDatas['id'])->update($editDatas);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
