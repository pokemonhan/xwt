<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Logics\PayTraits;

class UsersRechargeHistorie extends BaseModel
{
    use PayTraits;

    public const MODE_ARTIFICIAL = 1; //人工充值
    public const MODE_AUTOMATIC = 0; //自动充值

    public const STATUS_UNDERWAY = 0; //正在充值
    public const STATUS_SUCCESS = 1; //充值成功
    public const STATUS_FAILURE = 2; //充值失败
    public const STATUS_AUDIT_WAIT = 10; //正在审核
    public const STATUS_AUDIT_SUCCESS = 11; //审核成功
    public const STATUS_AUDIT_FAILURE = 12; //审核失败

    protected $guarded = ['id'];
}
