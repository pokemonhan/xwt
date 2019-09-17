<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/8/2019
 * Time: 3:20 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\LotterySerieLogics;

class LotterySerie extends BaseModel
{
    use LotterySerieLogics;

    protected $guarded = ['id'];

    public function lotteries()
    {
        return $this->hasMany(LotteryList::class, 'series_id', 'series_name');
    }
}
