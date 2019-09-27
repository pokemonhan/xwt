<?php

namespace App\Models\User;

use App\Models\BaseModel;

/**
 * 用户日工资
 */
class UserDaysalary extends BaseModel
{
    public const STATUS_YES = 1; //已发放
    public const STATUS_NO = 0; //未发放

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $appends = ['team_daysalary'];

    /**
     * 团队日工资
     * @return string
     */
    public function getTeamDaysalaryAttribute()
    {
        return number_format((($this->team_turnover - $this->team_bet_commission) * $this->daysalary_percentage / 100), 2);
    }
}
