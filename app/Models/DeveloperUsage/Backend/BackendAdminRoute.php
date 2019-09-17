<?php

namespace App\Models\DeveloperUsage\Backend;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;

class BackendAdminRoute extends BaseModel
{
    protected $guarded = ['id'];
    
    public function menu()
    {
        return $this->belongsTo(BackendSystemMenu::class, 'menu_group_id', 'id');
    }
}
