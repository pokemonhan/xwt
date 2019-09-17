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

class UpdateUserProfits implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    public function __construct($userId)
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
            $todayAccountsReportsUsers = FrontendUsersAccountsReport::where([
                ['created_at', '>', $today],
                ['user_id', '=', $this->userId],
            ])
                ->select('username', 'user_id', 'is_tester', 'parent_id')
                ->get();

        if (is_object($todayAccountsReportsUsers)) {
            foreach ($todayAccountsReportsUsers as $child) {
                $data['team_deposit'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_DEPOSIT_SIGN
                ) ;
                $data['team_withdrawal'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_WITHDRAWAL_SIGN
                ) ;
                $data['team_turnover'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_TURNOVER_SIGN
                ) ;
                $data['team_prize'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_PRIZE_SIGN
                ) ;
                $data['team_commission'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_COMMISSION_SIGN
                ) ;
                $data['team_bet_commission'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_BETCOMMISSION_SIGN
                ) ;
                $data['team_dividend'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_DVIVDEND_SIGN
                ) ;
                $data['team_daily_salary'] = self::getSumChildProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_DAILYSALARY_SIGN
                ) ;
                $data['team_profit'] = $data['team_prize']
                    + $data['team_commission'] + $data['team_bet_commission'] - $data['team_turnover'];

                $data['deposit'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_DEPOSIT_SIGN
                ) ;
                $data['withdrawal'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_WITHDRAWAL_SIGN
                ) ;
                $data['turnover'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_TURNOVER_SIGN
                ) ;
                $data['prize'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_PRIZE_SIGN
                ) ;
                $data['commission'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_COMMISSION_SIGN
                ) ;
                $data['bet_commission'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_BETCOMMISSION_SIGN
                ) ;
                $data['dividend'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_DVIVDEND_SIGN
                ) ;
                $data['daily_salary'] = self::getSumProfits(
                    $today,
                    $child->user_id,
                    UserProfits::TEAM_DAILYSALARY_SIGN
                ) ;
                $data['profit'] = $data['prize']
                    + $data['commission'] + $data['bet_commission'] - $data['turnover'];

                $data['date'] = $yesterday ?? $today;
                $data['user_id'] =  $child->user_id;
                $data['username'] =  $child->username;
                $data['is_tester'] =  $child->is_tester;
                $data['parent_id'] =  $child->parent_id;

                self::updateProfits($data);
            }
        }
    }

    private static function getSumProfits(string $date, int $user_id, array $type_sign) : float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['user_id', $user_id]
        ])
            ->whereIn('type_sign', $type_sign)
            ->sum('amount');
    }

    private static function getSumChildProfits(string $date, int $parent_id, array $type_sign) : float
    {
        return FrontendUsersAccountsReport::where([
            ['created_at', '>', $date],
            ['parent_id', $parent_id]
        ])
            ->whereIn('type_sign', $type_sign)
            ->sum('amount');
    }


    private static function updateProfits(array $data) : bool
    {
        if ($data['user_id'] && $data['date']) {
            $row = UserProfits::where([
                ['user_id', $data['user_id']],
                ['date', $data['date']]
            ])->first();

            if (empty($row)) {
                return (bool)UserProfits::create($data);
            } else {
                return (bool)$row->update($data);
            }
        }
    }
}
