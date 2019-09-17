<?php

namespace App\Http\SingleActions\Backend\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;
use App\Models\LotteryTrace;

class ReportManagementUserTraceAction
{
    /**
     * 玩家追号报表
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $this->specialSearch($contll, $inputDatas);

        $searchAbleFields = [
            'trace_serial_number',
            'username',
            'mode',
            'status',
            'lottery_sign',
            'method_sign',
        ];
        $fixedJoin = 1;
        $withTable = 'traceLists';
        $withSearchAbleFields = ['issue'];
        $field = 'created_at';
        $type = 'desc';
        $usersAccountsReport = $contll->generateSearchQuery(
            LotteryTrace::class,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            $withSearchAbleFields,
            $field,
            $type
        );
        return $contll->msgOut(true, $usersAccountsReport);
    }

    private function specialSearch($contll, $inputDatas)
    {
        if ((int) $inputDatas['get_sub'] === 1 && isset($inputDatas['username'])) {// 搜索下级
            $userIds = $this->getUserIds($inputDatas['username']);
            $contll->inputs['where_in']['key'] = 'user_id';
            $contll->inputs['where_in']['value'] = $userIds;
            unset($contll->inputs['username']);
        }
    }

    public function getUserIds($username)
    {
        $userELoq = FrontendUser::nameGetUser($username);
        if ($userELoq !== null) {
            $userIds = FrontendUser::getSubIds($userELoq->id);
        } else {
            $userIds = [];
        }
        return $userIds;
    }
}
