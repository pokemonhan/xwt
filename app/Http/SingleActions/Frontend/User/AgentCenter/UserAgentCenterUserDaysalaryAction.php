<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UserDaysalary;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;

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
        $username = $inputDatas['username'] ?? '';
        $dateTo = $inputDatas['date_to'] ?? date('Y-m-d');
        $count = $inputDatas['count'] ?? 15;

        $userInfo = $contll->currentAuth->user();
        $selfDaysalary = $this->model->where([['user_id', $userInfo->id],['date', '=', $dateTo]])->first();
        $where = [['parent_id', $userInfo->id], ['date', '=', $dateTo]];

        $data = [];
        $data['sum'] = $this->getDataSum($selfDaysalary, $username, $where);
        $data['self'] = $this->getDataSelf($userInfo, $selfDaysalary);
        $data['child'] = $this->getDataChild($where, $count);

        return $contll->msgOut(true, $data);
    }

    /**
     * 区间合计
     * @param mixed  $selfDaysalary 用户的日工资Eloq.
     * @param string $username      搜索的用户名.
     * @param array  $where         搜索条件.
     * @return object
     */
    private function getDataSum($selfDaysalary, string $username, array $where)
    {
        $dataSum = (object) [];

        if (empty($username)) {
            $teamUsers = $this->model->where($where)->get();
            $dataSum->team_turnover = $teamUsers->sum('team_turnover') + ($selfDaysalary->team_turnover ?? 0);
            $dataSum->turnover = $teamUsers->sum('turnover') + ($selfDaysalary->turnover ?? 0);
            $dataSum->daysalary = $teamUsers->sum('daysalary') + ($selfDaysalary->daysalary ?? 0);
        } else {
            $dataSum->team_turnover = $selfDaysalary->team_turnover ?? 0;
            $dataSum->turnover = $selfDaysalary->turnover ?? 0;
            $dataSum->daysalary = $selfDaysalary->daysalary ?? 0;
        }
        return $dataSum;
    }

    /**
     * 用户自己的日工资
     * @param FrontendUser $userInfo      当前用户Eloq.
     * @param mixed        $selfDaysalary 用户日工资Eloq.
     * @return object
     */
    private function getDataSelf(FrontendUser $userInfo, $selfDaysalary)
    {
        $self = (object) [];

        $self->user_id = $userInfo->id;
        $self->username = $userInfo->username;
        $self->daysalary_percentage = $selfDaysalary->daysalary_percentage ?? $userInfo->daysalary_percentage;
        $self->team_turnover = $selfDaysalary->team_turnover ?? 0;
        $self->turnover = $selfDaysalary->turnover ?? 0;
        $self->daysalary = $selfDaysalary->daysalary ?? 0;
        $self->remark = round($self->turnover, 2) . '*' . $self->daysalary_percentage . '%=' . round($self->turnover * $self->daysalary_percentage / 100, 2);

        return $self;
    }

    /**
     * 用户下级的日工资
     * @param array   $where 搜索条件.
     * @param integer $count 查询条数.
     * @return array
     */
    private function getDataChild(array $where, int $count)
    {
        $child = $this->model
        ->select('user_id', 'username', 'daysalary_percentage', 'team_turnover', 'turnover', 'daysalary')
        ->where($where)
        ->groupBy('user_id')
        ->paginate($count)
        ->toArray();

        foreach ($child['data'] as $childKey => $userDaysalary) {
            $child['data'][$childKey]['remark'] = round($userDaysalary['turnover'], 2) . '*' . $userDaysalary['daysalary_percentage'] . '%=' . round($userDaysalary['turnover'] * $userDaysalary['daysalary_percentage'] / 100, 2);
        }

        return $child;
    }
}
