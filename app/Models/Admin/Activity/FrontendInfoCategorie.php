<?php

namespace App\Models\Admin\Activity;

use App\Models\BaseModel;

class FrontendInfoCategorie extends BaseModel
{
    protected $guarded = ['id'];

    public function parentCategorie()
    {
        return $this->hasOne(__CLASS__, 'id', 'parent');
    }
}
