<?php

namespace App\Models\Admin;

use App\Models\Admin\Logics\SysConfiguresTraits;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemConfiguration extends BaseModel
{
    use SysConfiguresTraits;

    protected $guarded = ['id'];

    public function childs(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }
}
