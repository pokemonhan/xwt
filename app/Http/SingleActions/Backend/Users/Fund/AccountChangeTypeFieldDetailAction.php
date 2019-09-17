<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsTypesParam;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeFieldDetailAction
{
    protected $model;

    /**
     * @param  FrontendUsersAccountsTypesParam  $frontendUsersAccountsTypesParam
     */
    public function __construct(FrontendUsersAccountsTypesParam $frontendUsersAccountsTypesParam)
    {
        $this->model = $frontendUsersAccountsTypesParam;
    }

    /**
     * 帐变类型字段列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['id', 'label', 'param', 'created_at', 'updated_at'];
        $datas = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgout(true, $datas);
    }
}
