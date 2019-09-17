<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 5:54 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use App\Models\Game\Lottery\Logics\LotterySeriesWayLogics;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LotterySeriesWay extends BaseModel
{
    use LotterySeriesWayLogics;

    protected $guarded = ['id'];

    protected function getSeriesMethodIdsAttribute()
    {
        return explode(',', $this->attributes[ 'series_methods' ]);
    }

    public function basicWay(): HasOne
    {
        return $this->hasOne(LotteryBasicWay::class, 'id', 'basic_way_id');
    }
}
