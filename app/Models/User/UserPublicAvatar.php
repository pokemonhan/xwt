<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Logics\UserPublicAvatarTraits;

class UserPublicAvatar extends BaseModel
{
    use UserPublicAvatarTraits;

    protected $guarded = ['id'];
}
