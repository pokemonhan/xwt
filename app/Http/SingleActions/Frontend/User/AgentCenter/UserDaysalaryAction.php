<?php
namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\UserAgentCenter\UserDaysalaryRequest;
use App\Models\User\UserDaysalary;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserDaysalaryAction
{
    protected $model;
    private $selectSum = [
        'sum(team_turnover) as team_turnover',
        'sum(turnover) as turnover',
        'sum(daysalary) as daysalary',
    ];

    /**
     * UserDaysalaryAction constructor.
     * @param UserDaysalary $UserDaysalary
     */
    public function __construct(UserDaysalary $UserDaysalary)
    {
        $this->model = $UserDaysalary;
    }

    /**
     * 团队盈亏api
     * @param  FrontendApiMainController $contll
     * @param  UserDaysalaryRequest $request
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, UserDaysalaryRequest $request): JsonResponse
    {
        $data = [];
        $sum = (object) [];

        $username = $request->input('username') ?? '';
        $dateTo = $request->input('date_to') ?? date('Y-m-d');
        $count = $request->input('count') ?? 15;

        $userInfo = $contll->currentAuth->user();

        $where = [['parent_id', $userInfo->id], ['date', '=', $dateTo]];

        //区间合计 自己+下属的
        if (empty($username)) {
            $sum_team = $this->model->where($where)->select(DB::raw(implode(',', $this->selectSum)))->first();
            $sum_self = $this->model->where([
                ['user_id', $userInfo->id],
                ['date', '=', $dateTo],
            ])->select(DB::raw(implode(',', $this->selectSum)))
                ->first();
            $sum->team_turnover = $sum_team->team_turnover + $sum_self->team_turnover;
            $sum->turnover = $sum_team->turnover + $sum_self->turnover;
            $sum->daysalary = $sum_team->daysalary + $sum_self->daysalary;
            $data['sum'] = $sum;
        } else {
            $data['sum'] = $this->model->where(
                array_merge([['username', $username]], $where)
            )->select(DB::raw(implode(',', $this->selectSum)))
                ->first();
        }
        //自己
        $selectRaw = array_merge(['user_id', 'username', 'daysalary_percentage'], $this->selectSum);
        $data['self'] = $this->model->where([
            ['date', '=', $dateTo],
            ['user_id', $userInfo->id],
        ])->select(DB::raw(implode(',', $selectRaw)))
            ->first();
        $data['self']->remark = round($data['self']->turnover, 2) . '*' . $data['self']->daysalary_percentage . '%=' . round($data['self']->turnover * $data['self']->daysalary_percentage / 100, 2);
        //下级
        $data['child'] = $this->model->where($where)->select(DB::raw(implode(',', $selectRaw)))->groupBy('user_id')->paginate($count)->toArray();

        foreach ($data['child']['data'] as $k => $v) {
            $data['child']['data'][$k]['remark'] = round($v['turnover'], 2) . '*' . $v['daysalary_percentage'] . '%=' . round($v['turnover'] * $v['daysalary_percentage'] / 100, 2);
        }

        return $contll->msgOut(true, $data);
    }
}
