<?php

namespace App\Http\SingleActions\Backend\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsReport;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;

class ReportManagementUserAccountChangeAction
{
    /**
     * 玩家帐变报表
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $this->specialSearch($contll, $inputDatas);

        $accountChangeEloq = new FrontendUsersAccountsReport();
        $searchAbleFields = [
            'serial_number',
            'username',
            'type_sign',
            'is_tester',
            'parent_id',
            'in_out',
            'from_admin_id',
            'lottery_id'
        ];
        $fixedJoin = 2;
        $withTable = [
            'project:id,series_id,lottery_sign,method_name,mode,times,ip',
            'admin:id,name'
        ];
        $withSearchAbleFields = [
            ['mode', 'ip'],
            []
        ];
        $field = 'created_at';
        $type = 'desc';
        $usersAccountsReport = $contll->generateSearchQuery(
            $accountChangeEloq,
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

        if (isset($inputDatas['min_price']) && isset($inputDatas['max_price'])) {
            $contll->inputs['extra_where']['method'] = 'whereBetween';
            $contll->inputs['extra_where']['key'] = 'amount';
            $contll->inputs['extra_where']['value'] = [
                $contll->inputs['min_price'],
                $contll->inputs['max_price']
            ];
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
