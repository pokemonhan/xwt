<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/15/2019
 * Time: 2:43 AM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\LotteryPrizeGroupLogic;

class LotteryPrizeGroup extends BaseModel
{
    use LotteryPrizeGroupLogic;
    protected $guarded = ['id'];
}
