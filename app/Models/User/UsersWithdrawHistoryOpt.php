<?php

namespace App\Models\User;

use App\Models\BaseModel;

class UsersWithdrawHistoryOpt extends BaseModel
{
    protected $guarded = ['id'];
    public function withdrawHistory()
    {
        return $this->belongsTo(UsersWithdrawHistorie::class, 'withdraw_id', 'id');
    }
}
