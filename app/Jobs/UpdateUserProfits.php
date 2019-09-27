<?php

namespace App\Jobs;

use App\Models\User\Fund\FrontendUsersAccountsReport;
use App\Models\User\UserProfits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\User\FrontendUser;

/**
 * 更新用户盈亏
 */
class UpdateUserProfits implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var integer
     */
    protected $userId;

    /**
     * @param integer $userId 用户id.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $user = FrontendUser::find($this->userId);

        if ($user !== null) {
            $teamData = $this->getTeamData($today, $user); //团队信息
            $data = $this->getAllData($today, $user, $teamData);

            self::updateProfits($data);
        }
    }

    /**
     * @param  string  $date     日期.
     * @param  integer $userId   用户id.
     * @param  array   $typeSign 字段类型.
     * @return float
     */
    private static function getSumProfits(string $date, int $userId, array $typeSign): float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['user_id', $userId],
        ])
            ->whereIn('type_sign', $typeSign)
            ->sum('amount');
    }

    /**
     * @param  string  $date     日期.
     * @param  integer $parentId 用户id.
     * @param  array   $typeSign 字段类型.
     * @return float
     */
    private static function getSumChildProfits(string $date, int $parentId, array $typeSign): float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['parent_id', $parentId],
        ])
            ->whereIn('type_sign', $typeSign)
            ->sum('amount');
    }

    /**
     * 更新用户盈亏数据
     * @param array $data 数据.
     * @return void
     */
    private static function updateProfits(array $data): void
    {
        if ($data['user_id'] && $data['date']) {
            $userProfits = UserProfits::where([
                ['user_id', $data['user_id']],
                ['date', $data['date']],
            ])->first();

            if (empty($userProfits)) {
                UserProfits::create($data);
            } else {
                $userProfits->update($data);
            }
        }
    }

    /**
     * 获取用户的团队盈亏数据
     * @param string       $today 日期.
     * @param FrontendUser $user  FrontendUser.
     * @return array
     */
    private function getTeamData(string $today, FrontendUser $user)
    {
        $data['team_deposit'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_DEPOSIT_SIGN,
        );
        $data['team_withdrawal'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_WITHDRAWAL_SIGN,
        );
        $data['team_turnover'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_TURNOVER_SIGN,
        );
        $data['team_prize'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_PRIZE_SIGN,
        );
        $data['team_commission'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_COMMISSION_SIGN,
        );
        $data['team_bet_commission'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_BETCOMMISSION_SIGN,
        );
        $data['team_dividend'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_DVIVDEND_SIGN,
        );
        $data['team_daily_salary'] = self::getSumChildProfits(
            $today,
            $user->id,
            UserProfits::TEAM_DAILYSALARY_SIGN,
        );
        $data['team_profit'] = $data['team_prize']
             + $data['team_commission'] + $data['team_bet_commission'] - $data['team_turnover'];
        //净盈亏 = 派奖总额+投注返点+下级返点+活动礼金+日工资-投注总额（现在缺少活动礼金）
        $data['team_net_profit_total'] = $data['team_prize'] + $data['team_commission'] + $data['team_bet_commission'] + $data['team_daily_salary'] - $data['team_turnover'];

        return $data;
    }

    /**
     * 获取用户的团队盈亏所有数据
     * @param string       $today 日期.
     * @param FrontendUser $user  FrontendUser.
     * @param array        $data  团队相关的数据.
     * @return array
     */
    private function getAllData(string $today, FrontendUser $user, array $data)
    {
        $data['deposit'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_DEPOSIT_SIGN,
        );
        $data['withdrawal'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_WITHDRAWAL_SIGN,
        );
        $data['turnover'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_TURNOVER_SIGN,
        );
        $data['prize'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_PRIZE_SIGN,
        );
        $data['commission'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_COMMISSION_SIGN,
        );
        $data['bet_commission'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_BETCOMMISSION_SIGN,
        );
        $data['dividend'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_DVIVDEND_SIGN,
        );
        $data['daily_salary'] = self::getSumProfits(
            $today,
            $user->id,
            UserProfits::TEAM_DAILYSALARY_SIGN,
        );
        $data['profit'] = $data['prize'] + $data['commission'] + $data['bet_commission'] - $data['turnover'];
        //净盈亏 = 派奖总额+投注返点+下级返点+活动礼金+日工资-投注总额（现在缺少活动礼金）
        $data['net_profit_total'] = $data['prize'] + $data['commission'] + $data['bet_commission'] + $data['daily_salary'] - $data['turnover'];
        $data['date'] = $today;
        $data['user_id'] = $user->id;
        $data['username'] = $user->username;
        $data['is_tester'] = $user->is_tester;
        $data['parent_id'] = $user->parent_id;
        return $data;
    }
}
