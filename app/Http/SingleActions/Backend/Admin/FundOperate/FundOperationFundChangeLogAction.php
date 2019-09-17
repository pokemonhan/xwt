<?php

namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Illuminate\Http\JsonResponse;

class FundOperationFundChangeLogAction
{
    /**
     * 查看管理员人工充值额度记录
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $datas = BackendAdminRechargehumanLog::where('admin_id', $inputDatas['admin_id'])
            ->whereBetween('created_at', [$inputDatas['start_time'], $inputDatas['end_time']])
            ->get()
            ->toArray();
        return $contll->msgout(true, $datas);
    }
}
