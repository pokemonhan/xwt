<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Frontend\Traits\FrontendModelTraits;

class FrontendAllocatedModel extends BaseModel
{
    use FrontendModelTraits;

    protected $guarded = ['id'];

    public function childs()
    {
        return $this->hasMany(__CLASS__, 'pid', 'id');
    }
}
