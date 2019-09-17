<?php

namespace App\Models\DeveloperUsage\TaskScheduling;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\TaskScheduling\Logics\CronJobLogics;

class CronJob extends BaseModel
{
    use CronJobLogics;

    public const STATUS_OPEN = 1;
    public const STATUS_CLOSE = 0;

    protected $guarded = ['id'];
}
