<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsType;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeEditAction
{
    protected $model;

    /**
     * @param  FrontendUsersAccountsType  $frontendUsersAccountsType
     */
    public function __construct(FrontendUsersAccountsType $frontendUsersAccountsType)
    {
        $this->model = $frontendUsersAccountsType;
    }

    /**
     * 编辑帐变类型
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $editData = $inputDatas;
        $param = implode(',', $inputDatas['param']);
        $editData['param'] = $param;
        $accountsTypeEloq = $this->model::find($inputDatas['id']);
        $contll->editAssignment($accountsTypeEloq, $editData);
        $accountsTypeEloq->save();
        if ($accountsTypeEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $accountsTypeEloq->errors()->messages());
        }
        return $contll->msgout(true);
    }
}
