<?php
/**
 * 用户返点佣金 model
 */
namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\Logics\UserCommissionsTrait;

class UserCommissions extends BaseModel
{
    use UserCommissionsTrait;

    protected $guarded = ['id'];

    public const STATUS_WAIT = 0 ;
    public const STATUS_DONE = 1 ;
}
