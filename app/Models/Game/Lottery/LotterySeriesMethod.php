<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 5:29 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\SeriesMethodsLogics;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LotterySeriesMethod extends BaseModel
{
    use SeriesMethodsLogics;

    protected $guarded = ['id'];

    public function basicMethod(): HasOne
    {
        return $this->hasOne(LotteryBasicMethod::class, 'id', 'basic_method_id');
    }
}
