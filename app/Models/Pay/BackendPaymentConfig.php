<?php

namespace App\Models\Pay;

use App\Models\BaseModel;

class BackendPaymentConfig extends BaseModel
{
    public const STATUS_ENABLE = 1; //启用
    public const STATUS_DISABLE = 0; //禁用
    public const DIRECTION_IN = 1; //入款
    public const DIRECTION_OUT = 0; //出款
    public function paymentInfos()
    {
        return $this->hasMany(BackendPaymentInfo::class, 'config_id', 'id');
    }
}
