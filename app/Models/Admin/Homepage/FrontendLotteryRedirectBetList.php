<?php

namespace App\Models\Admin\Homepage;

use App\Models\Admin\Homepage\Logics\FrontendLotteryRedirectBetListTraits;
use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssueRule;
use App\Models\Game\Lottery\LotteryList;

class FrontendLotteryRedirectBetList extends BaseModel
{
    use FrontendLotteryRedirectBetListTraits;
    protected $guarded = ['id'];

    public function lotteries()
    {
        return $this->hasOne(LotteryList::class, 'id', 'lotteries_id');
    }

    public function issueRule()
    {
        return $this->hasMany(LotteryIssueRule::class, 'lottery_id', 'lotteries_sign');
    }
}
