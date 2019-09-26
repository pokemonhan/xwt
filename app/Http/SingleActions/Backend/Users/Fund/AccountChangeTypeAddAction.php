<?php

namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsType;
use Illuminate\Http\JsonResponse;

/**
 * 添加帐变类型
 */
class AccountChangeTypeAddAction
{
    /**
     * @var FrontendUsersAccountsType
     */
    protected $model;

    /**
     * @param FrontendUsersAccountsType $frontendUsersAccountsType 帐变类型model.
     */
    public function __construct(FrontendUsersAccountsType $frontendUsersAccountsType)
    {
        $this->model = $frontendUsersAccountsType;
    }

    /**
     * 添加帐变类型
     * @param BackEndApiMainController $contll     Controller.
     * @param array                    $inputDatas 传递的参数.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $addData = $inputDatas;
        $param = implode(',', $inputDatas['param']);
        if (!empty($param)) {
            $addData['param'] = $param;
        } else {
            unset($addData['param']);
        }
        $accountsTypeEloq = $this->model;
        $accountsTypeEloq->fill($addData); //$inputDatas
        $accountsTypeEloq->save();
        if ($accountsTypeEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $accountsTypeEloq->errors()->messages());
        }
        return $contll->msgout(true);
    }
}
