<?php

namespace App\Models\Admin\Payment;

use App\Models\BaseModel;

/**
 * Class BackendPaymentConfig
 * @package App\Models\Admin\Payment
 */
class BackendPaymentConfig extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $visible = ['payment_name', 'payment_sign','payment_type_sign','payment_type_name','payment_vendor_sign','payment_vendor_name','direction','request_url'];
}
