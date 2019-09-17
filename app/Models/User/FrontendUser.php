<?php

namespace App\Models\User;

use App\Models\Admin\FrontendUsersPrivacyFlow;
use App\Models\SystemPlatform;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\Logics\FrontendUserTraits;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Admin\Notice\FrontendMessageNotice;

class FrontendUser extends Authenticatable implements JWTSubject
{
    use Notifiable, FrontendUserTraits;

    const TYPE_TOP_AGENT = 1;
    const TYPE_AGENT = 2;
    const TYPE_USER = 3;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'register_time' => 'datetime',
        'last_login_time' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function platform()
    {
        return $this->hasOne(SystemPlatform::class, 'platform_id', 'platform_id');
    }

    public function account()
    {
        return $this->hasOne(FrontendUsersAccount::class, 'user_id', 'id');
    }
    //用户冻结历史
    public function userAdmitedFlow()
    {
        return $this->hasMany(FrontendUsersPrivacyFlow::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }

    //用户个人资料
    public function specific()
    {
        return $this->hasOne(FrontendUsersSpecificInfo::class, 'user_id', 'id');
    }

    //用户站内信
    public function message()
    {
        return $this->hasMany(FrontendMessageNotice::class, 'receive_user_id', 'id');
    }
}
