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
     * @var BackendPaymentVendor $model 第三方厂商表控制器.
     */
    protected $model;

    /**
     * PaymentVendorsEditAction constructor
     * @param BackendPaymentVendor $backendPaymentVendor 第三方厂商表控制器.
     */
    public function __construct(BackendPaymentVendor $backendPaymentVendor)
    {
        $this->model = $backendPaymentVendor;
    }

    /**
     * 编辑第三方厂商表
     * @param PaymentVendorsController $contll     第三方厂商表控制器.
     * @param array                    $inputDatas 前台获取的参数.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentVendorsController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payment_vendor = BackendPaymentVendor::find($inputDatas['id']);
            //判断修改第三方厂商表的数据是否存在
            $ifExistVendor = $this->isExistVendor($inputDatas, $payment_vendor);
            if (!empty($ifExistVendor) && isset($ifExistVendor)) {
                return $contll->msgOut(false, [], '102800');
            }
            //处理拼接数据
            if (!empty($inputDatas['whitelist_ips']) && isset($inputDatas['whitelist_ips'])) {
                $inputDatas['whitelist_ips'] = trim($inputDatas['whitelist_ips'], '|');
            }
            //执行添加第三方厂商表操作
            $payment_vendor->fill($inputDatas);
            $payment_vendor->save();
            //更新支付配置表信息
            $this->updateConfig($payment_vendor);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    /**
     * @param mixed $payment_vendor 获取第三方厂商表信息.
     * @return void
     */
    private function updateConfig($payment_vendor)
    {
        $updateDatas = [];
        $updateDatas['payment_vendor_name'] = $payment_vendor['payment_type_name'];
        $updateDatas['payment_vendor_sign'] = $payment_vendor['payment_type_sign'];
        BackendPaymentConfig::where('payment_type_id', $payment_vendor['id'])->update($updateDatas);
    }

    /**
     * 判断第三方厂商表的数据是否存在
     * @param mixed $inputDatas     前台获取的参数.
     * @param mixed $payment_vendor 第三方厂商信息.
     * @return mixed
     */
    private function isExistVendor($inputDatas, $payment_vendor)
    {
        //判断第三方厂商表的数据是否存在
        if (!empty($inputDatas['payment_vendor_sign']) && isset($inputDatas['payment_vendor_sign'])) {
            $array = [
                ['payment_vendor_name', '=', $payment_vendor['payment_vendor_name']],
                ['payment_vendor_sign', '=', $inputDatas['payment_vendor_sign']],
            ];
        } elseif (!empty($inputDatas['payment_vendor_name']) && isset($inputDatas['payment_vendor_name'])) {
            $array = [
                ['payment_vendor_name', '=', $inputDatas['payment_vendor_name']],
                ['payment_vendor_sign', '=', $payment_vendor['payment_vendor_sign']],
            ];
        } elseif (empty($inputDatas['payment_vendor_name']) && empty($inputDatas['payment_vendor_sign']) && !isset($inputDatas['payment_vendor_name']) && !isset($inputDatas['payment_vendor_sign'])) {
            $array = [];
        } else {
            $array = [
                ['payment_vendor_name', '=', $inputDatas['payment_vendor_name']],
                ['payment_vendor_sign', '=', $inputDatas['payment_vendor_sign']],
            ];
        }
        if (!empty($array)) {
            return BackendPaymentVendor::where($array)->first();
        }
    }
}
