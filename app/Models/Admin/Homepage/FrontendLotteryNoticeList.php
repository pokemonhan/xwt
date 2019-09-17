<?php

namespace App\Models\Admin\Homepage;

use App\Models\Admin\Homepage\Logics\FrontendLotteryNoticeListTraits;
use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;

class FrontendLotteryNoticeList extends BaseModel
{
    use FrontendLotteryNoticeListTraits;

    protected $guarded = ['id'];

    public function lottery()
    {
        return $this->hasOne(LotteryList::class, 'en_name', 'lotteries_id');
    }
}
