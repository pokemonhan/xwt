<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/15/2019
 * Time: 2:40 AM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\LotteryPrizeDetailLogic;

class LotteryPrizeDetail extends BaseModel
{
    use LotteryPrizeDetailLogic;
    protected $guarded = ['id'];
    public static $seriesFullFillAble = ['sd', 'ssl', 'lotto', 'p3p5'];
}
