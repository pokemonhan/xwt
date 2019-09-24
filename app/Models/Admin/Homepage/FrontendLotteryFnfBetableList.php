<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 热门玩法
 */
class FrontendLotteryFnfBetableList extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 玩法
     * @return HasOne
     */
    public function method() :HasOne
    {
        return $this->hasOne(FrontendLotteryFnfBetableMethod::class, 'id', 'method_id');
    }

    /**
     * 当前奖期
     * @return HasOne
     */
    public function currentIssue() :HasOne
    {
        return $this->hasOne(LotteryIssue::class, 'lottery_id', 'lotteries_id')
            ->where('end_time', '>', time())
            ->orderBy('begin_time', 'ASC');
    }
}
