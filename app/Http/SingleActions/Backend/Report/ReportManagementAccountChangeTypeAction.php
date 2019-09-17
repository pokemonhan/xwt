<?php

namespace App\Http\SingleActions\Backend\Report;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsType;
use Illuminate\Http\JsonResponse;

class ReportManagementAccountChangeTypeAction
{
    /**
     * 帐变类型列表
     * @param   BackEndApiMainController  $contll
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $datas = FrontendUsersAccountsType::select('name', 'sign')->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
