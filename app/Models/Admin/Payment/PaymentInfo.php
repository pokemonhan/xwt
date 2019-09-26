<?php

namespace App\Models\Admin\Payment;

use App\Models\BaseModel;

/**
 * Class PaymentInfo
 * @package App\Models\Admin\Payment
 */
class PaymentInfo extends BaseModel
{
    /**
     * @var array $guarded Array.
     */
    protected $guarded = ['id'];

    /**
     * 隐藏属性
     * @var array $hidden Array.
     */
    protected $hidden = ['merchant_secret', 'public_key', 'private_key', 'app_id','front_remark','payment_sign','payment_vendor_sign'];
}
