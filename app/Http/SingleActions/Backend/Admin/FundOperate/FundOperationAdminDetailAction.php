<?php

namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class FundOperationAdminDetailAction
{
    /**
     * 额度管理列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $group = BackendAdminRechargePermitGroup::select('group_id')->pluck('group_id')->toArray();
        $contll->inputs['extra_where']['method'] = 'whereIn';
        $contll->inputs['extra_where']['key'] = 'group_id';
        $contll->inputs['extra_where']['value'] = $group;
        $eloqM = new BackendAdminUser();
        $fixedJoin = 1; //number of joining tables
        $withTable = 'operateAmount';
        $searchAbleFields = ['name', 'group_id'];
        $withSearchAbleFields = ['fund'];
        $orderFields = 'id';
        $orderFlow = 'asc';
        $data = $contll->generateSearchQuery(
            $eloqM,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            $withSearchAbleFields,
            $orderFields,
            $orderFlow
        );
        $sysConfiguresEloq = SystemConfiguration::where('sign', 'admin_recharge_daily_limit')->first();
        $finalData['admin_user'] = $data;
        if ($sysConfiguresEloq !== null) {
            $finalData['dailyFundLimit'] = $sysConfiguresEloq->value;
        }
        return $contll->msgOut(true, $finalData);
    }
}
