<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User\FrontendUser;
use App\Models\User\UserDaysalary;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User\UserProfits;

/**
 * 用户日工资计算脚本
 * 每日凌晨2点 运行一次，更新数据到user_daysalary
 */
class UserDaysalaryControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserDaysalary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日工资计算脚本';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::yesterday()->toDateString();

        $usersProject = Project::where('created_at', '>', $date)
            ->select(DB::raw('sum(total_cost) as total_cost,user_id'))
            ->whereIn('status', [Project::STATUS_LOST, Project::STATUS_WON, Project::STATUS_PRIZE_SENT])
            ->groupby('user_id')
            ->get();

        foreach ($usersProject as $usersProjectItem) {
            $user = $usersProjectItem->frontendUser;
            if ($user === null) {
                Log::channel('daysalary')->info('用户id：'.$usersProjectItem->user_id.'不存在,计算日工资失败');
                continue;
            }

            $userProfits = UserProfits::getUserProfits($user, $date);
            $data = $this->setDaysalaryData($user, $date, $userProfits);
            $data['turnover'] = $usersProjectItem->total_cost;
            $data['daysalary'] = $usersProjectItem->total_cost * $user->daysalary_percentage / 100 ;
            $data['team_turnover'] = $usersProjectItem->total_cost;

            DB::beginTransaction();

            $resource = UserDaysalary::updateUserDaysalary($data);
            Log::channel('daysalary')->info($resource['log_info']);
            if ($resource['success'] === false) {
                DB::rollback();
                continue;
            }

            //上级的日工资比用户高时，需要上供给上级
            if ($parentInfo = $user->parent) {
                $parentData = $this->setDaysalaryData($parentInfo, $date);
                $parentData['daysalary'] = $usersProjectItem->total_cost*($parentInfo->daysalary_percentage-$user->daysalary_percentage)/100;
                $parentData['team_turnover'] = $usersProjectItem->total_cost;

                $resource = UserDaysalary::updateUserDaysalary($parentData);
                Log::channel('daysalary')->info($resource['log_info']);
                if ($resource['success'] === false) {
                    DB::rollback();
                    continue;
                }
            }
            DB::commit();
        }
    }

    /**
     * 设置日工资基本数据
     * @param FrontendUser $user        用户.
     * @param string       $date        日期.
     * @param mixed        $userProfits 团队信息.
     * @return  array
     */
    private function setDaysalaryData(FrontendUser $user, string $date, $userProfits = null)
    {
        $data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'is_tester' => $user->is_tester,
            'parent_id' => $user->parent_id,
            'date' => $date,
            'daysalary_percentage' => $user->daysalary_percentage,
        ];

        if ($userProfits !== null) {
            $data['bet_commission'] = $userProfits->bet_commission ?? 0;
            $data['commission'] = $userProfits->commission ?? 0;
            $data['team_bet_commission'] = $userProfits->team_bet_commission ?? 0;
            $data['team_commission'] = $userProfits->team_commission ?? 0;
        }

        return $data;
    }
}
