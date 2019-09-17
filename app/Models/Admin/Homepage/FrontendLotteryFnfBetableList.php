<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssue;

class FrontendLotteryFnfBetableList extends BaseModel
{
    protected $guarded = ['id'];

    public function method()
    {
        return $this->hasOne(FrontendLotteryFnfBetableMethod::class, 'id', 'method_id');
    }

    public function currentIssue()
    {
        return $this->hasOne(LotteryIssue::class, 'lottery_id', 'lotteries_id')
            ->where('end_time', '>', time())
            ->orderBy('id', 'ASC');
    }
}
