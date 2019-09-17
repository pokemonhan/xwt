<?php
/**
 * 用户日工资计算脚本
 * 每日凌晨2点 运行一次，更新数据到user_daysalary
 */

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User\FrontendUser;
use App\Models\User\UserDaysalary;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $today = Carbon::yesterday()->toDateString();

        $usersList = Project::where([
            ['projects.created_at', '>', $today],
        ])
            ->whereIn('projects.status', [Project::STATUS_LOST,Project::STATUS_WON])
            ->select(DB::raw(implode(',', ['sum(total_cost) as total_cost','projects.user_id',
                'daysalary_percentage','frontend_users.parent_id',
                'frontend_users.username','frontend_users.is_tester',])))
            ->groupby('projects.username')
            ->leftJoin('frontend_users', function ($join) {
                $join->on('frontend_users.id', '=', 'user_id');
            })
            ->get();

        if (is_object($usersList)) {
            foreach ($usersList as $child) {
                $data['daysalary'] =$child->total_cost * $child->daysalary_percentage / 100 ;

                $data['daysalary_percentage'] = $child->daysalary_percentage;
                $data['turnover'] = $child->total_cost;
                $data['team_turnover'] = 0 ; //$this->get_team_turnover($child->user_id); 改为下级上供累计计算

                $data['status'] = 0;
                $data['date'] = $today;
                $data['user_id'] =  $child->user_id;
                $data['username'] =  $child->username;
                $data['is_tester'] =  $child->is_tester;
                $data['parent_id'] =  $child->parent_id;

                $this->updateUserDaysalary($data);

                //上供给上级
                if ($child->parent_id > 0) {
                    $parent_info = FrontendUser::where([['id',$child->parent_id]])->first();
                    if (is_object($parent_info)) {
                        $parent_info = $parent_info->toArray();
                    }
                    $parent_data['user_id'] = $child->parent_id ;
                    $parent_data['date'] = $today;
                    $parent_data['daysalary_percentage'] = array_get($parent_info, 'daysalary_percentage') ;
                    $parent_data['daysalary'] = (array_get($parent_info, 'daysalary_percentage')
                            - $child->daysalary_percentage) / 100 * $child->total_cost;
                    $parent_data['team_turnover'] = $child->total_cost;
                    $parent_data['username'] = array_get($parent_info, 'username') ;
                    $parent_data['is_tester'] = array_get($parent_info, 'is_tester') ;
                    $parent_data['parent_id'] = array_get($parent_info, 'parent_id') ;
                    $this->updateUserDaysalary($parent_data);
                }
            }
        }
    }


//    private function get_team_turnover(int $user_id) : float
//    {
//        $today =   Carbon::yesterday()->toDateString();
//        return Project::where([
//            ['created_at', '>', $today],
//            ['parent_id', '=', $user_id],
//        ])
//            ->whereIn('status', [Project::STATUS_LOST,Project::STATUS_WON])
//            ->sum('total_cost');
//    }

    public function updateUserDaysalary(array $data) : bool
    {
        if ($data['user_id'] && $data['date']) {
            $row = UserDaysalary::where([
                ['user_id', $data['user_id']],
                ['date', $data['date']]
            ])->first();

            if (empty($row)) {
                Log::channel('daysalary')->info('新建用户日工资'.json_encode($data));
                return (bool)UserDaysalary::create($data);
            } else {
                $data['daysalary'] = $data['daysalary'] + $row['daysalary'] ;
                $data['team_turnover'] = $data['team_turnover'] + $row['team_turnover'] ;
                Log::channel('daysalary')->info('更新用户日工资'.json_encode($data));
                return (bool)$row->update($data);
            }
        }
    }
}
