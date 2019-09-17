<?php
namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\UserAgentCenter\UserProfitsRequest;
use App\Models\Project;
use App\Models\User\FrontendUser;
use App\Models\User\UserProfits;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserProfitsAction
{
    protected $model;
    private $selectSum = [
        'sum(team_deposit) as team_deposit',
        'sum(team_withdrawal) as team_withdrawal',
        'sum(team_turnover) as team_turnover',
        'sum(team_prize) as team_prize',
        'sum(team_profit) as team_profit',
        'sum(team_commission) as team_commission',
        'sum(team_dividend) as team_dividend',
        'sum(team_daily_salary) as team_daily_salary',
    ];

    /**
     * UserAgentCenterAction constructor.
     * @param UserProfits $UserProfits
     */
    public function __construct(UserProfits $UserProfits)
    {
        $this->model = $UserProfits;
    }

    /**
     * 团队盈亏api
     * @param  FrontendApiMainController $contll
     * @param  UserProfitsRequest $request
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, UserProfitsRequest $request): JsonResponse
    {
        $data = [];
        $sum = (object) [];

        $username = $request->input('username') ?? '';
        $dateTo = $request->input('date_to') ?? date('Y-m-d');
        $dateFrom = $request->input('date_from') ?? date('Y-m-01');
        $count = $request->input('count') ?? 15;

        $userInfo = $contll->currentAuth->user();

        if (in_array($userInfo->type, [2, 3])) {
            $where = [['parent_id', $userInfo->id], ['date', '>=', $dateFrom], ['date', '<=', $dateTo]];
        } else {
            $where = [['user_id', $userInfo->id], ['date', '>=', $dateFrom], ['date', '<=', $dateTo]];
        }

        //区间合计 自己+下属的
        if (empty($username)) {
            if (in_array($userInfo->type, [2, 3])) {
                $sum_team = $this->model->where($where)->select(DB::raw(implode(',', $this->selectSum)))->first();
                $sum_self = $this->model->where([
                    ['user_id', $userInfo->id],
                    ['date', '>=', $dateFrom],
                    ['date', '<=', $dateTo],
                ])->select(DB::raw(implode(',', $this->selectSum)))
                    ->first();
                $sum->team_deposit = $sum_team->team_deposit + $sum_self->team_deposit;
                $sum->team_withdrawal = $sum_team->team_withdrawal + $sum_self->team_withdrawal;
                $sum->team_turnover = $sum_team->team_turnover + $sum_self->team_turnover;
                $sum->team_prize = $sum_team->team_prize + $sum_self->team_prize;
                $sum->team_profit = $sum_team->team_profit + $sum_self->team_profit;
                $sum->team_commission = $sum_team->team_commission + $sum_self->team_commission;
                $sum->team_dividend = $sum_team->team_dividend + $sum_self->team_dividend;
                $sum->team_daily_salary = $sum_team->team_daily_salary + $sum_self->team_daily_salary;
                //新注册人数(时间区间内注册的人数)、总下级人数
                //$sum->child_num = $this->getChildNum($userInfo->id);
                //$sum->new_child_num =  $this->getChildNum($userInfo->id, [$dateFrom, $dateTo]);
                //时间区间内投注人数
                //$sum->bet_child_num =  $this->getBetNum($userInfo->id, [$dateFrom, $dateTo]);
            } else {
                $sum = $this->model->where([
                    ['user_id', $userInfo->id],
                    ['date', '>=', $dateFrom],
                    ['date', '<=', $dateTo],
                ])->select(DB::raw(implode(',', $this->selectSum)))
                    ->first();
            }
            $data['sum'] = $sum;
        } else {
            $data['sum'] = $this->model->where(
                array_merge([['username', $username]], $where)
            )->select(DB::raw(implode(',', $this->selectSum)))
                ->first();
        }
        //自己
        $selectRaw = array_merge(['user_id', 'username'], $this->selectSum);
        $data['self'] = $this->model->where([
            ['date', '>=', $dateFrom],
            ['date', '<=', $dateTo],
            ['user_id', $userInfo->id],
        ])->select(DB::raw(implode(',', $selectRaw)))
            ->first();

        if (in_array($userInfo->type, [2, 3])) {
            //下级
            $data['child'] = $this->model
                ->where($where)
                ->select(DB::raw(implode(',', $selectRaw)))
                ->groupBy('user_id')
                ->paginate($count);
        }

        return $contll->msgOut(true, $data);
    }

    /**
     * 获取下级注册人数
     * @param int $userId
     * @param array $date
     * @return int
     */
    private function getChildNum(int $userId, array $date = []): int
    {
        $num = FrontendUser::where([
            ['parent_id', '=', $userId],
        ]);
        if (($date)) {
            $num->where([
                ['created_at', '>=', $date[0]],
                ['created_at', '<=', $date[1]],
            ]);
        }

        return $num->count();
    }

    /**
     * 获得时间区间内的投注人数
     * @param int $userId
     * @param array $date
     * @return int
     */
    private function getBetNum(int $userId, array $date = []): int
    {
        return Project::where([
            ['parent_id', '=', $userId],
            ['created_at', '>=', $date[0]],
            ['created_at', '<=', $date[1]],
        ])
            ->groupBy('user_id')
            ->count();
    }
}
