<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersAccount;
use Illuminate\Support\Arr;
use App\Models\Project;
use App\Models\User\UsersRechargeHistorie;
use App\Models\User\UsersWithdrawHistorie;
use App\Models\User\UserCommissions;

class UserAgentCenterTeamReportAction
{
    protected $model;
    protected $projectStatus;
    protected $rechargeStatus;
    protected $withdrawStatus;
    /**
     * @param  FrontendUser  $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;

        $this->projectStatus = [
            Project::STATUS_LOST,
            Project::STATUS_WON,
            Project::STATUS_PRIZE_SENT,
        ];

        $this->rechargeStatus = [
            UsersRechargeHistorie::STATUS_SUCCESS,
            UsersRechargeHistorie::STATUS_AUDIT_SUCCESS,
        ];

        $this->withdrawStatus = [
            UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS,
            UsersWithdrawHistorie::STATUS_ARRIVAL,
        ];
    }

    /**
     * 团队报表
     * @param  FrontendApiMainController $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $pageSize = $inputDatas['page_size'] ?? 15;
        $user = $contll->partnerUser;

        $where = [];
        $data = [];

        $data['team_data'] = $this->getTeamData($user, $inputDatas, $where);
        $data['user_data'] = $this->getUserData($user, $inputDatas, $pageSize, $where);

        return $contll->msgOut(true, $data);
    }

    //团队统计数据
    private function getTeamData($user, $inputDatas, &$where)
    {
        $data = [];
        $teamUsers = $user->children;
        $userTeamId = $teamUsers->pluck('id')->toArray();
        $this->insertTimeCondtions($inputDatas, $teamUsers, $where, $data); //时间搜索

        $data['team_num'] = $teamUsers->count(); //团队人数
        //团队投注人数
        $data['team_bet_num'] = Project::where($where)
            ->whereIn('status', $this->projectStatus)
            ->where('parent_id', $user->id)
            ->groupBy('user_id')
            ->get()->count();
        //团队充值人数
        $data['team_recharge_num'] = UsersRechargeHistorie::where($where)
            ->whereIn('status', $this->rechargeStatus)
            ->whereIn('user_id', $userTeamId)
            ->groupBy('user_id')
            ->get()->count();
        //团队充值总额
        $data['team_recharge'] = UsersRechargeHistorie::where($where)
            ->whereIn('status', $this->rechargeStatus)
            ->whereIn('user_id', $userTeamId)
            ->sum('real_amount');
        //团队提现总额
        $data['team_withdraw'] = UsersWithdrawHistorie::where($where)
            ->whereIn('status', $this->withdrawStatus)
            ->where('parent_id', $user->id)
            ->sum('real_amount');
        //团队总余额
        $data['team_balance'] = FrontendUsersAccount::whereIn('user_id', $userTeamId)->sum('balance');
        //团队下注总额
        $data['team_bet_amount'] = Project::where($where)
            ->whereIn('status', $this->projectStatus)
            ->where('parent_id', $user->id)
            ->sum('total_cost');
        //团队奖金总额
        $data['team_bonus_amount'] = Project::where($where)
            ->where('status', Project::STATUS_PRIZE_SENT)
            ->where('parent_id', $user->id)
            ->sum('bonus');
        //团队返点总额
        $data['team_commission'] = UserCommissions::where($where)
            ->where('status', UserCommissions::STATUS_DONE)
            ->whereIn('user_id', $userTeamId)
            ->sum('amount');
        //团队盈亏
        $data['team_profit'] = (string) ($data['team_bonus_amount']+$data['team_commission']-$data['team_bet_amount']);

        return $data;
    }

    //用户列表
    private function getUserData($user, $inputDatas, $pageSize, $where)
    {
        $userList = $this->getUserList($user->id, $inputDatas, $where, $pageSize);

        $userArr = $userList->toArray();
        $userData = [];

        foreach ($userList as $userItem) {
            $data = [
                'user_id' => $userItem->id,
                'username' => $userItem->username
            ];
            //用户余额
            $data['user_balance'] = $userItem->account->balance ?? 0;
            //用户充值总额
            $data['user_recharge'] = UsersRechargeHistorie::where($where)
                ->whereIn('status', $this->rechargeStatus)
                ->where('user_id', $userItem->id)
                ->sum('real_amount');
            //用户提现总额
            $data['user_withdraw'] = UsersWithdrawHistorie::where($where)
                ->whereIn('status', $this->withdrawStatus)
                ->where('user_id', $userItem->id)
                ->sum('real_amount');
            //用户下注总额
            $data['user_bet_amount'] = Project::where($where)
                ->whereIn('status', $this->projectStatus)
                ->where('user_id', $userItem->id)
                ->sum('total_cost');
            //用户奖金总额
            $data['user_bonus_amount'] = Project::where($where)
                ->where('status', Project::STATUS_PRIZE_SENT)
                ->where('user_id', $userItem->id)
                ->sum('bonus');
            //用户返点总额
            $data['user_commission'] = UserCommissions::where($where)
                ->where('status', UserCommissions::STATUS_DONE)
                ->where('user_id', $userItem->id)
                ->sum('amount');
            //用户盈亏
            $data['user_profit'] = (string) ($data['user_bonus_amount']+$data['user_commission']-$data['user_bet_amount']);
            $userData[] = $data;
        }

        $userArr['data'] = $userData;

        return $userArr;
    }

    //时间条件
    private function insertTimeCondtions($inputDatas, $teamUsers, &$where, &$data)
    {
        if (isset($inputDatas['time_condtions'])) {
            $timeConditions = Arr::wrap(json_decode($inputDatas['time_condtions'], true));
            $where = array_merge($where, $timeConditions);
            $betweenTime = Arr::pluck($timeConditions, '2');
            $data['team_new'] = $teamUsers->whereBetween('created_at', $betweenTime)->count();
        } else {
            $data['team_new'] = $teamUsers->count();
        }
    }

    private function getUserList($userId, $inputDatas, $where, $pageSize)
    {
        $userEloq = new FrontendUser();
        //username
        if (isset($inputDatas['username'])) {
            $where = ['username' => $inputDatas['username']];
            $userEloq = $userEloq->where($where);
        } else {
            $userEloq = $userEloq->where($where)->where('parent_id', $userId);
        }

        return $userEloq->orderBy('created_at', 'desc')->paginate($pageSize);
    }
}
