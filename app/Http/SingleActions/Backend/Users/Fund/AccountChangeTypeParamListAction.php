<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsTypesParam;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeParamListAction
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
     * 操作帐变类型时需要的字段列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $data = $this->model::select('id', 'label')->get()->toArray();
        return $contll->msgout(true, $data);
    }
}
