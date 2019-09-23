<?php

namespace App\Models\Pay\Logics;

use App\Models\Pay\BackendPaymentConfig;
use App\Models\Pay\PaymentInfo;

trait PaymentInfoTraits
{
    /**
     * @return mixed
     */
    public static function getPaymentInfoLists()
    {
        return PaymentInfo::join('backend_payment_configs', 'backend_payment_configs.id', '=', 'payment_infos.config_id')
            ->where('backend_payment_configs.status', BackendPaymentConfig::STATUS_ENABLE)
            ->where('payment_infos.status', PaymentInfo::STATUS_ENABLE)
            ->where('backend_payment_configs.direction', BackendPaymentConfig::DIRECTION_IN)
            ->where('payment_infos.direction', PaymentInfo::DIRECTION_IN)
            ->select(
                'backend_payment_configs.payment_type_sign', //支付种类的标记
                'backend_payment_configs.payment_type_name', //支付种类的名称
                'backend_payment_configs.request_mode',  //请求方式
                'payment_infos.front_name', //前台名称
                'payment_infos.front_remark',  //前台备注
                'payment_infos.payment_sign',  //支付方式标记
                'payment_infos.min',  //最小值
                'payment_infos.max',  //最大值
            )
            ->orderByDesc('sort')
            ->get();
    }
}
