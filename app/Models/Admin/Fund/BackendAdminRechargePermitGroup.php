<?php

namespace App\Models\Admin\Fund;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BackendAdminRechargePermitGroup extends BaseModel
{
    protected $guarded = ['id'];

    public function admins(): HasMany
    {
        return $this->hasMany(BackendAdminUser::class, 'group_id', 'group_id');
    }
}
