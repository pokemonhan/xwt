<?php

namespace App\Models\DeveloperUsage\Menu;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Menu\Traits\MenuLogics;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BackendSystemMenu extends BaseModel
{
    use MenuLogics;
    protected $redisFirstTag = 'ms_menu';

    public function childs(): HasMany
    {
        return $this->hasMany(__CLASS__, 'pid', 'id');
    }
}
