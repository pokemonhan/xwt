<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Logics\PayTraits;

class UsersWithdrawHistorie extends BaseModel
{
    use PayTraits;

    public const STATUS_AUDIT_WAIT = 0; //等待审核 或 未认领
    public const STATUS_AUDIT_FAILURE = 1; //审核驳回
    public const STATUS_AUDIT_SUCCESS = 2; //审核通过
    public const STATUS_ARRIVAL = 3; //提现到账

    protected $guarded = ['id'];
    public function withdrawHistoryOpt()
    {
        return $this->hasOne(UsersWithdrawHistoryOpt::class, 'withdraw_id', 'id');
    }
}
