<?php

namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class UserBankCityListsAction
{
    /**
     * 添加银行卡时选择的城市列表
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $data = UsersRegion::select('id', 'region_id', 'region_name')
            ->where('region_parent_id', $inputDatas['region_parent_id'])
            ->get()
            ->toArray();
        return $contll->msgOut(true, $data);
    }
}
