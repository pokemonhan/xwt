<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Route;

class FrontendWebRoute extends BaseModel
{
    protected $guarded = ['id'];
    protected $appends = ['real_route','route_to_update'];

    public function getRealRouteAttribute()
    {
        if (Route::has($this->route_name)) {
            return route($this->route_name, [], false);
        } else {
            return '';
        }
    }

    public function getRouteToUpdateAttribute(): ?int
    {
        if (Route::has($this->route_name)) {
            return 0;
        } else {
            return 1;
        }
    }
}
