<?php

namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\SystemConfiguration;
use Exception;
use Illuminate\Http\JsonResponse;

class FundOperationEveryDayFundAction
{
    /**
     * 设置每日的管理员人工充值额度
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $sysConfiguresEloq = SystemConfiguration::where('sign', 'admin_recharge_daily_limit')->first();
        if ($sysConfiguresEloq === null) {
            return $contll->msgOut(false, [], '101301');
        }
        try {
            $editData = ['value' => $inputDatas['fund']];
            $sysConfiguresEloq->fill($editData);
            $sysConfiguresEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
