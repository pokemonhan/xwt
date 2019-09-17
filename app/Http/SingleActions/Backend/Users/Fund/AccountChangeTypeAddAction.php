<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsType;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeAddAction
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
     * 添加帐变类型
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $param = implode(',', $inputDatas['param']);
        $addData = $inputDatas;
        $addData['param'] = $param;
        $accountsTypeEloq = new $this->model;
        $accountsTypeEloq->fill($addData); //$inputDatas
        $accountsTypeEloq->save();
        if ($accountsTypeEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $accountsTypeEloq->errors()->messages());
        }
        return $contll->msgout(true);
    }
}
