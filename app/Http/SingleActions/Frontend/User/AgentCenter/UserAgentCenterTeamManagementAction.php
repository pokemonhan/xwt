<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersAccount;
use Illuminate\Support\Arr;

class UserAgentCenterTeamManagementAction
{
    protected $model;

    /**
     * @param  FrontendUser  $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 团队管理
     * @param  FrontendApiMainController $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $pageSize = $inputDatas['page_size'] ?? 15;

        $userELoq = new FrontendUser();
        $userELoq = $this->getUserData($userELoq, $inputDatas, $contll->partnerUser->id, $pageSize);

        $data = [];

        foreach ($userELoq as $userItem) {
            $specific = $userItem->specific;
            $userTeam = $userItem->children;
            $userTeamId = $userTeam->pluck('id')->toArray();
            $userTeamBalance = FrontendUsersAccount::whereIn('user_id', $userTeamId)->sum('balance');
            if (isset($inputDatas['min_team_balance']) && isset($inputDatas['max_team_balance'])) {
                if ($userTeamBalance < $inputDatas['min_team_balance'] || $userTeamBalance > $inputDatas['max_team_balance']) {
                    continue;
                }
            }
            $userLastLoginTime = $userItem->last_login_time === null ? null : $userItem->last_login_time->toDateTimeString();

            $data[] = [
                'id' => $userItem->id,
                'username' => $userItem->username,
                'prize_group' => $userItem->prize_group,
                'total_members' => $specific->total_members ?? 0,
                'register_at' => $userItem->created_at->toDateTimeString(),
                'last_login_time' => $userLastLoginTime,
                'team_balance' => $userTeamBalance, //团队余额
            ];
        }

        return $contll->msgOut(true, $data);
    }

    private function getUserData($userELoq, $inputDatas, $userId, $pageSize)
    {
        $where = [];

        //parent_id
        if (isset($inputDatas['parent_id'])) {
            $parentUser = FrontendUser::find($inputDatas['parent_id']);
            if ($parentUser !== null) {
                $ridArr = explode('|', $parentUser->rid);
                if (in_array($userId, $ridArr)) {
                    $where[] = ['parent_id', $inputDatas['parent_id']];
                }
            }
        } else {
            $where[] = ['parent_id', $userId];
        }

        //username
        if (isset($inputDatas['username'])) {
            $where[] = ['username', $inputDatas['username']];
        }

        //time_condtions
        if (isset($inputDatas['time_condtions'])) {
            $timeConditions = Arr::wrap(json_decode($inputDatas['time_condtions'], true));
            $where = array_merge($where, $timeConditions);
        }

        //price_group_condtions
        if (isset($inputDatas['price_group_condtions'])) {
            $priceGroupCondtions = Arr::wrap(json_decode($inputDatas['price_group_condtions'], true));
            $where = array_merge($where, $priceGroupCondtions);
        }

        return $userELoq->where($where)->orderBy('created_at', 'desc')->paginate($pageSize);
    }
}
