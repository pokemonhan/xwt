<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class FrontendSystemLog extends BaseModel
{
    public const PHONE = 1;
    public const DESKSTOP = 2;
    public const ROBOT = 3;
    public const MOBILE = 4;
    public const TABLET = 5;
    public const OTHER = 6;
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
