<?php

namespace App\Models\User;

use App\Models\BaseModel;

class UserDaysalary extends BaseModel
{
    public const STATUS_YES = 1; //已发放
    public const STATUS_NO = 0; //未发放
    protected $guarded = ['id'];
}
