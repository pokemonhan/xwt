<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\UsersRechargeHistorie;
use Illuminate\Http\JsonResponse;

class UserHandleUserRechargeHistoryAction
{
    /**
     * 用户充值记录
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $datas = UsersRechargeHistorie::select('user_name', 'amount', 'deposit_mode', 'status', 'created_at')
            ->where('user_id', $inputDatas['user_id'])
            ->whereBetween('created_at', [$inputDatas['start_time'], $inputDatas['end_time']])
            ->get()
            ->toArray();
        return $contll->msgOut(true, $datas);
    }
}
