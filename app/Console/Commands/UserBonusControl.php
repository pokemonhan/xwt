<?php
/**
 * 分红计算处理脚本
 * 每月1/15号运行一次，分红计算数据到user_bonus
 */
namespace App\Console\Commands;

use App\Models\User\UserBonus;
use App\Models\User\UserProfits;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserBonusControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserBonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '分红计算脚本';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();

        switch (date('d')) {
            case '1':
                $dateFrom = date('Y-m-15', strtotime('-1 months -1 day'));
                $dateTo = date('Y-m-d', (int) strtotime(date('Y-m-1') . '-1 day'));
                break;

            case '15':
                $dateFrom = date('Y-m-01');
                $dateTo = date('Y-m-15');
                break;
            default:
                $dateFrom = date('Y-m-01');
                $dateTo = date('Y-m-15');
                //               exit();
        }

        $whereDate = [
            ['date', '>=', $dateFrom],
            ['date', '<=', $dateTo],
        ];

        $selectSum = [
            'user_profits.username',
            'user_profits.user_id',
            'user_profits.is_tester',
            'user_profits.parent_id',
            'bonus_percentage',
            'sum(team_deposit) as team_deposit',
            'sum(team_withdrawal) as team_withdrawal',
            'sum(team_turnover) as team_turnover',
            'sum(team_prize) as team_prize',
            'sum(team_profit) as team_profit',
            'sum(team_commission) as team_commission',
            'sum(team_dividend) as team_dividend',
            'sum(team_daily_salary) as team_daily_salary',
        ];

        $UserProfits = UserProfits::where($whereDate)
            ->select(DB::raw(implode(',', $selectSum)))
            ->groupby('user_id')
            ->leftJoin('frontend_users', function ($join) {
                $join->on('frontend_users.id', '=', 'user_id');
            })
            ->get();

        if (is_object($UserProfits)) {
            foreach ($UserProfits as $child) {
                $data['period'] = $today;
                $data['user_id'] = $child->user_id;
                $data['username'] = $child->username;
                $data['is_tester'] = $child->is_tester;
                $data['parent_user_id'] = $child->parent_id;

                $data['salary_total'] = $child->team_daily_salary;
                $data['dividend_total'] = $child->team_dividend;
                $data['commission_total'] = $child->team_commission;
                $data['prize_total'] = $child->team_prize;
                $data['turnover_total'] = $child->team_turnover;

                $data['bet_counts'] = $this->countBet($whereDate, $child->user_id); //下级有效投注数量
                $data['bonus_percentage'] = $child->bonus_percentage;
                $data['net_profit_total'] = $child->team_profit;

                $data['bonus_total'] = $data['net_profit_total'] * $data['bonus_percentage'] / 100;

                self::updateBonus($data);
            }
        }
    }

    public function countBet($whereDate, $userId): int
    {
        $where = array_merge($whereDate, [['parent_id', '=', $userId]]);
        return (int) UserProfits::where($where)->groupby('user_id')->count();
    }

    public static function updateBonus(array $data): bool
    {
        if ($data['user_id'] && $data['period']) {
            $row = UserBonus::where([
                ['user_id', $data['user_id']],
                ['period', $data['period']],
            ])->first();

            if (empty($row)) {
                return (bool) UserBonus::create($data);
            } else {
                return (bool) $row->update($data);
            }
        }
    }
}
