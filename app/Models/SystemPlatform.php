<?php

namespace App\Models;

use App\Models\Admin\BackendAdminUser;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemPlatform extends BaseModel
{
    public function partnerAdminUsers(): HasMany
    {
        return $this->hasMany(BackendAdminUser::class, 'platform_id', 'platform_id');
    }
}
