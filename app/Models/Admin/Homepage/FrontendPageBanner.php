<?php

namespace App\Models\Admin\Homepage;

use App\Models\Admin\Activity\FrontendActivityContent;
use App\Models\BaseModel;

class FrontendPageBanner extends BaseModel
{
    protected $guarded = ['id'];

    public function activity()
    {
        return $this->hasOne(FrontendActivityContent::class, 'id', 'activity_id');
    }
}
