<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LotteryMethodsLayout extends BaseModel
{

    protected $guarded = ['id'];

    public function getFormattedNumberRuleAttribute()
    {
        return $this->numberRule->value;
    }

    public function getFormattedDisplayCodeAttribute()
    {
        return $this->layoutDisplay->display_name;
    }

    public function numberRule(): HasOne
    {
        return $this->hasOne(LotteryMethodsNumberButtonRule::class, 'id', 'rule_id');
    }

    public function layoutDisplay(): HasOne
    {
        return $this->hasOne(LotteryMethodsLayoutDisplay::class, 'display_code', 'display_code');
    }
}
