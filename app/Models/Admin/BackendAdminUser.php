<?php

namespace App\Models\Admin;

use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\SystemPlatform;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class BackendAdminUser extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'email_verified_at' => 'datetime',
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

    public function accessGroup()
    {
        return $this->hasOne(BackendAdminAccessGroup::class, 'id', 'group_id');
    }

    public function operateAmount()
    {
        return $this->hasOne(BackendAdminRechargePocessAmount::class, 'admin_id', 'id');
    }
}
