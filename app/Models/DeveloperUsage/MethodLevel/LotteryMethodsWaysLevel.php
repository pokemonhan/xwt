<?php

namespace App\Models\DeveloperUsage\MethodLevel;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\Traits\MethodLevelLogics;

class LotteryMethodsWaysLevel extends BaseModel
{
    use MethodLevelLogics;

    protected $guarded = ['id'];
}
