<?php
/**
 * 用户团队盈亏 model
 */
namespace App\Models\User;

use App\Models\BaseModel;

class UserProfits extends BaseModel
{
    const TEAM_DEPOSIT_SIGN         = ['recharge','artificial_recharge'];
    const TEAM_WITHDRAWAL_SIGN      = ['withdraw_finis'];
    const TEAM_TURNOVER_SIGN        = ['bet_cost','trace_cost'];
    const TEAM_PRIZE_SIGN           = ['game_bonus'];
    const TEAM_COMMISSION_SIGN      = ['commission'];
    const TEAM_BETCOMMISSION_SIGN   = ['bet_commission'];
    const TEAM_DVIVDEND_SIGN        = ['gift'];            //促销红利
    const TEAM_DAILYSALARY_SIGN     = ['day_salary'];       //日工资


    protected $guarded = ['id'];
}
