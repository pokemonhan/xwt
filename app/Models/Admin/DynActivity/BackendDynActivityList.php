<?php

namespace App\Models\Admin\DynActivity;

use App\Models\BaseModel;

class BackendDynActivityList extends BaseModel
{
    public const STATUS_NORMAL = 1;
    public const STATUS_DISABLE = 0;
    protected $guarded = ['id'];
}
