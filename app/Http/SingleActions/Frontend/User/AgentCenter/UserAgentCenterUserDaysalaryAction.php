<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UserDaysalary;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 日工资报表
 */
class UserAgentCenterUserDaysalaryAction
{
    /**
     * [Model]
     * @var UserDaysalary
     */
    protected $model;

    /**
     * UserDaysalaryAction constructor.
     * @param UserDaysalary $UserDaysalary UserDaysalary.
     */
    public function __construct(UserDaysalary $UserDaysalary)
    {
        $this->model = $UserDaysalary;
    }

    /**
     * 日工资
     * @param FrontendApiMainController $contll     Controller.
     * @param array                     $inputDatas 接收的参数.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $date = $inputDatas['date'] ?? date('Y-m-d');

        $userInfo = $contll->currentAuth->user();
        $selfDaysalary = $this->model->where([['user_id', $userInfo->id],['date', '=', $date]])->first();

        $data = [];

        //获取自己的日分红信息
        if ((int) $inputDatas['user_type'] === 0 || (int) $inputDatas['user_type'] === 1) {
            $data['self'] = $this->getDataSelf($userInfo, $selfDaysalary);
        }

        //获取下级的日分红信息
        if ((int) $inputDatas['user_type'] === 0 || (int) $inputDatas['user_type'] === 2) {
            $where = [['parent_id', $userInfo->id], ['date', '=', $date]];
            if (isset($inputDatas['username'])) {
                $where[] = ['username', $inputDatas['username']];
            }
            $count = $inputDatas['count'] ?? 15;
            $data['child'] = $this->getDataChild($where, $count);
        }
        return $contll->msgOut(true, $data);
    }

    /**
     * 用户自己的日工资
     * @param FrontendUser $userInfo      当前用户Eloq.
     * @param mixed        $selfDaysalary 用户日工资Eloq.
     * @return array
     */
    private function getDataSelf(FrontendUser $userInfo, $selfDaysalary)
    {
        $self = [];

        $self['user_id'] = $userInfo->id;
        $self['username'] = $userInfo->username;

        $self['daysalary_percentage'] = $selfDaysalary->daysalary_percentage ?? $userInfo->daysalary_percentage; //日工资比例

        $self['team_turnover'] = $selfDaysalary->team_turnover ?? 0; //团队流水

        $self['turnover'] = $selfDaysalary->turnover ?? 0; //用户个人流水

        $self['team_bet_commission'] = $selfDaysalary->team_bet_commission ?? 0; //团队投注返点

        $self['team_daysalary'] = $selfDaysalary->team_daysalary ?? 0; //团队日工资

        $self['daysalary'] = $selfDaysalary->daysalary ?? 0; //用户日工资

        return $self;
    }

    /**
     * 用户下级的日工资
     * @param array   $where 搜索条件.
     * @param integer $count 查询条数.
     * @return  LengthAwarePaginator
     */
    private function getDataChild(array $where, int $count) :LengthAwarePaginator
    {
        $child = $this->model
        ->select('user_id', 'username', 'daysalary_percentage', 'team_turnover', 'team_bet_commission', 'turnover', 'daysalary')
        ->where($where)
        ->groupBy('user_id')
        ->paginate($count);

        return $child;
    }
}
