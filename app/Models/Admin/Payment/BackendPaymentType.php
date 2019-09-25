<?php

namespace App\Models\Admin\Payment;

use App\Models\BaseModel;

/**
 * Class BackendPaymentType
 * @package App\Models\Admin\Payment
 */
class BackendPaymentType extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 隐藏属性
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];
}
