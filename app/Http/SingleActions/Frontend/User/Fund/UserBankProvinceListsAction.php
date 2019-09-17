<?php

namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class UserBankProvinceListsAction
{
    /**
     * 添加银行卡时选择的省份列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = UsersRegion::select('id', 'region_id', 'region_name')->where('region_level', 1)->get()->toArray();
        return $contll->msgOut(true, $data);
    }
}
