<?php

namespace App\Models\Pay\Logics;

use App\Models\Pay\BackendPaymentConfig;
use App\Models\Pay\BackendPaymentType;
use App\Models\Pay\PaymentInfo;

trait PaymentInfoTraits
{
    /**
     * @return mixed
     */
    public static function getPaymentInfoLists()
    {
        $paymentInfos = PaymentInfo::join('backend_payment_configs', 'backend_payment_configs.id', '=', 'payment_infos.config_id')
            ->join('backend_payment_types', 'backend_payment_types.payment_type_sign', '=', 'payment_infos.payment_type_sign')
            ->where('backend_payment_configs.status', BackendPaymentConfig::STATUS_ENABLE)
            ->where('payment_infos.status', PaymentInfo::STATUS_ENABLE)
            ->where('backend_payment_configs.direction', BackendPaymentConfig::DIRECTION_IN)
            ->where('payment_infos.direction', PaymentInfo::DIRECTION_IN)
            ->select(
                'backend_payment_configs.payment_type_sign', //支付种类的标记
                'backend_payment_configs.payment_type_name', //支付种类的名称
                'backend_payment_types.payment_ico', //支付种类的图标
                'backend_payment_configs.request_mode',  //请求方式
                'payment_infos.front_name', //前台名称
                'payment_infos.front_remark',  //前台备注
                'payment_infos.payment_sign',  //支付方式标记
                'payment_infos.min',  //最小值
                'payment_infos.max',  //最大值
                'backend_payment_configs.banks_code',  //银行码
            )
            ->orderByDesc('sort')
            ->get();
        $paymentTypes = BackendPaymentType::get()->toArray();
        $data = [];
        foreach ($paymentTypes as $paymentType) {
            $data[$paymentType['payment_type_sign']] = [
                'payment_ico' => $paymentType['payment_ico'],
                'payment_type_sign' => $paymentType['payment_type_sign'],
                'payment_type_name' => $paymentType['payment_type_name'],
            ];
        }
        foreach ($paymentInfos as $paymentInfo) {
            if (!empty($paymentInfo->banks_code)) {
                $tmpData1 = explode('|', $paymentInfo->banks_code);
                foreach ($tmpData1 as $value2) {
                    [$bankCode] = explode('=', $value2);
                    $tmpData2[] = $data[$bankCode];
                }
                $paymentInfo->banks_code = $tmpData2;
                unset($tmpData2);
            } else {
                $paymentInfo->banks_code = null;
            }
        }
        return $paymentInfos;
    }
}
