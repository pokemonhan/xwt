<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 5:53 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\LotteryBasicWayLogics;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotteryBasicWay extends BaseModel
{
    use LotteryBasicWayLogics;

    protected $guarded = ['id'];

    public function seriesWays(): HasMany
    {
        return $this->hasMany(LotterySeriesWay::class, 'basic_way_id', 'id');
    }
}
