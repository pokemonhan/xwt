<?php

namespace App\Models\User;

use App\Models\Admin\FrontendUsersPrivacyFlow;
use App\Models\Project;
use App\Models\SystemPlatform;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\Fund\FrontendUsersBankCard;
use App\Models\User\Logics\FrontendUserTraits;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Admin\Notice\FrontendMessageNotice;

/**
 * Class FrontendUser
 * @package App\Models\User
 */
class FrontendUser extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use FrontendUserTraits;

    /**
     * @var TYPE_TOP_AGENT
     */
    public const TYPE_TOP_AGENT = 1;
    /**
     * @var TYPE_AGENT
     */
    public const TYPE_AGENT = 2;
    // 没用到  暂时注释
    // const TYPE_USER = 3;
    public const FROZEN_TYPE_NO_WITHDRAWAL = 3;
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * 自定义字段
     * @var array $appends
     */
    protected $appends = ['total_members', 'team_balance'];
    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'register_time' => 'datetime',
        'last_login_time' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function platform()
    {
        return $this->hasOne(SystemPlatform::class, 'platform_id', 'platform_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(FrontendUsersAccount::class, 'user_id', 'id');
    }
    /**
     * 用户冻结历史
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAdmitedFlow()
    {
        return $this->hasMany(FrontendUsersPrivacyFlow::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }
    /**
     * 用户个人资料
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function specific()
    {
        return $this->hasOne(FrontendUsersSpecificInfo::class, 'user_id', 'id');
    }
    /**
     * 用户站内信
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function message()
    {
        return $this->hasMany(FrontendMessageNotice::class, 'receive_user_id', 'id');
    }
    /**
     * 提现记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdrawHistories()
    {
        return $this->hasMany(UsersWithdrawHistorie::class, 'user_id', 'id');
    }

    /**
     * 用户的充值记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rechargeHistories()
    {
        return $this->hasMany(UsersRechargeHistorie::class, 'user_id', 'id');
    }

    /**
     * 用户的打码记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    /**
     * 用的返点记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commissions()
    {
        return $this->hasMany(UserCommissions::class, 'user_id', 'id');
    }

    /**
     * 用户的日工资
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function daysalaries()
    {
        return $this->hasMany(UserDaysalary::class, 'user_id', 'id');
    }

    /**
     * 用户的分红记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonuses()
    {
        return $this->hasMany(UserBonus::class, 'user_id', 'id');
    }

    /**
     * 找子级
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany($this, 'parent_id', 'id');
    }

    /**
     * 团队总人数
     * @return integer
     */
    public function getTotalMembersAttribute()
    {
        return $this->specific->total_members ?? 0;
    }

    /**
     * 团队总余额
     * @return float
     */
    public function getTeamBalanceAttribute()
    {
        return $this->getTeamBalance();
    }

    /**
     * 玩家绑定的银行卡
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banks()
    {
        return $this->hasMany(FrontendUsersBankCard::class, 'user_id', 'id');
    }
}
