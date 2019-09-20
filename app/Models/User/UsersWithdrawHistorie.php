<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Fund\FrontendUsersBankCard;
use App\Models\User\Logics\PayTraits;

class UsersWithdrawHistorie extends BaseModel
{
    use PayTraits;

    public const STATUS_FAIL = -3; //失败
    public const STATUS_REFUSE = -2; //拒绝
    public const STATUS_AUDIT_WAIT = 0; //等待审核 或 未认领
    public const STATUS_CLAIMED = 4; //已认领
    public const STATUS_AUDIT_FAILURE = 1; //审核驳回
    public const STATUS_AUDIT_SUCCESS = 2; //审核通过
    public const STATUS_ARRIVAL = 3; //提现到账

    public const STATUS_SUCCESS = 5; //成功
    protected $guarded = ['id'];
    //每条提现记录拥有一条相应的操作
    public function withdrawHistoryOpt()
    {
        return $this->hasOne(UsersWithdrawHistoryOpt::class, 'withdraw_id', 'id');
    }
    //每条提现记录属于一个用户
    public function frontendUser()
    {
        return $this->belongsTo(FrontendUser::class, 'user_id', 'id');
    }

    //每条提现记录属于一个银行卡
    public function bank()
    {
        return $this->belongsTo(FrontendUsersBankCard::class, 'card_id', 'id');
    }
}
