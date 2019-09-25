<?php
namespace App\Models\User;

use App\Models\BaseModel;

/**
 * 用户团队盈亏 model
 */
class UserProfits extends BaseModel
{
    public const TEAM_DEPOSIT_SIGN         = ['recharge','artificial_recharge']; //充值总额
    public const TEAM_WITHDRAWAL_SIGN      = ['withdraw_finis'];                 //提现总额
    public const TEAM_TURNOVER_SIGN        = ['bet_cost'];                       //投注总额
    public const TEAM_PRIZE_SIGN           = ['game_bonus'];                     //派奖总额
    public const TEAM_COMMISSION_SIGN      = ['commission'];                     //下级返点
    public const TEAM_BETCOMMISSION_SIGN   = ['bet_commission'];                 //投注返点
    public const TEAM_DVIVDEND_SIGN        = ['gift'];                           //促销红利
    public const TEAM_DAILYSALARY_SIGN     = ['day_salary'];                     //日工资

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
