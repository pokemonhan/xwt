<?php

namespace App\Models\User\Supports;

use App\Models\BaseModel;
use App\Models\User\Supports\Logics\FrontendUserHelpCenterLogics;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FrontendUsersHelpCenter extends BaseModel
{
    use FrontendUserHelpCenterLogics;

    protected $guarded = ['id'];

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'pid', 'id');
    }
}
