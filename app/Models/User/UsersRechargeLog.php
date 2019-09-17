<?php

namespace App\Models\User;

use App\Models\BaseModel;

class UsersRechargeLog extends BaseModel
{

    const ARTIFICIAL = 1; //人工充值
    const AUTOMATIC = 0; //自动充值

    protected $guarded = ['id'];
}
