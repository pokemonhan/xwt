<?php

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendSystemNoticeList extends BaseModel
{
    public const ARTIFICIAL = 1;
    public const AUDIT = 2;
    public const FUND = 3;

    protected $guarded = ['id'];
}
