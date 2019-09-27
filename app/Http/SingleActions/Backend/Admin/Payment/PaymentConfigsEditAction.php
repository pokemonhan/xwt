<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentConfigsController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\BackendPaymentType;
use App\Models\Admin\Payment\BackendPaymentVendor;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentConfigsEditAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentConfigsEditAction
{
    use BaseCache;

    /**
     * @var BackendPaymentConfig $model 支付配置信息模型.
     */
    protected $model;

    /**
     * PaymentConfigsEditAction constructor.
     * @param BackendPaymentConfig $backendPaymentConfig 支付配置信息模型.
     */
    public function __construct(BackendPaymentConfig $backendPaymentConfig)
    {
        $this->model = $backendPaymentConfig;
    }

    /**
     * 编辑支付方式详情表
     * @param PaymentConfigsController $contll     主控制器.
     * @param array                    $inputDatas 前端获取编辑信息.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentConfigsController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //根据前端获取的第三方配置表ID和支付类型表ID获取需要的参数
            $inputDatas = $this->getAddDatasByid($inputDatas);
            //执行添加操作
            if (!empty($inputDatas['id']) && isset($inputDatas['id'])) {
                $payment_config = BackendPaymentConfig::find($inputDatas['id']);

                //判断支付方式配置表是否重复编辑信息
                $isExistPaymentConfig = $this->isExistPaymentConfig($inputDatas, $payment_config);
                if (!empty($isExistPaymentConfig) && isset($isExistPaymentConfig)) {
                    return $contll->msgOut(false, [], '102700');
                }

                //执行添加操作
                $payment_config->fill($inputDatas);
                $payment_config->save();
                //更新支付方式详情表信息
                $this->updatePaymentInfo($payment_config, $inputDatas);
                DB::commit();
            }
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    /**
     * 根据前端获取的第三方配置表ID和支付类型表ID获取需要的参数
     * @param mixed $inputDatas 根据前端获取的第三方配置表ID和支付类型表ID获取需要的参数.
     * @return array
     */
    private function getAddDatasByid($inputDatas)
    {
        //根据前端获取的第三方配置表ID和支付类型表ID获取需要的参数
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
            'code' => $payment_type->is_bank,
        ];
        $addDatas['banks_code'] = json_encode($addDatas['banks_code']);
        $inputDatas = array_merge($inputDatas, $addDatas);
        return $inputDatas;
    }

    /**
     * 更新支付方式详情表信息
     * @param mixed $payment_config 获取支付方式详情表参数.
     * @param mixed $inputDatas     前端获取参数.
     * @return void.
     */
    private function updatePaymentInfo($payment_config, $inputDatas)
    {
        $updateDatas = [];
        $updateDatas['direction'] = $payment_config->direction;
        $updateDatas['payment_name'] = $payment_config->payment_name;
        $updateDatas['payment_sign'] = $payment_config->payment_sign;
        $updateDatas['payment_vendor_sign'] = $payment_config->payment_vendor_sign;
        $updateDatas['payment_type_sign'] = $payment_config->payment_type_sign;
        $updateDatas['request_url'] = $payment_config->request_url;
        $updateDatas['status'] = $payment_config->status;
        $updateDatas['back_url'] = rtrim(configure('back_url'), '/') . '/' . $updateDatas['direction'] . '/' . ltrim($updateDatas['payment_sign'], '/');//支付方式的返回地址
        //执行更新支付方式详情表信息
        PaymentInfo::where('config_id', $inputDatas['id'])->update($updateDatas);
    }

    /**
     * 判断支付方式配置表是否重复编辑信息
     * @param mixed $inputDatas     前端获取的参数.
     * @param mixed $payment_config 支付方式列表配置表信息.
     * @return mixed
     */
    private function isExistPaymentConfig($inputDatas, $payment_config)
    {
        //判断支付方式详情表是否重复编辑信息
        if (!empty($inputDatas['payment_sign']) && isset($inputDatas['payment_sign'])) {
            $array = [
                ['payment_name', '=', $payment_config->payment_name],
                ['payment_sign', '=', $inputDatas['payment_sign']],
            ];
        } elseif (!empty($inputDatas['payment_name']) && isset($inputDatas['payment_name'])) {
            $array = [
                ['payment_name', '=', $inputDatas['payment_name']],
                ['payment_sign', '=', $payment_config->payment_sign],
            ];
        } elseif (empty($inputDatas['payment_name']) && empty($inputDatas['payment_sign']) && !isset($inputDatas['payment_name']) && !isset($inputDatas['payment_sign'])) {
            $array = [];
        } else {
            $array = [
                ['payment_name', '=', $inputDatas['payment_name']],
                ['payment_sign', '=', $inputDatas['payment_sign']],
            ];
        }
        if (!empty($array)) {
            return BackendPaymentConfig::where($array)->first();
        }
    }
}
