<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUsersBankCard;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class UserHandleBankCardListAction
{
    protected $model;

    /**
     * @param  FrontendUsersBankCard  $frontendUsersBankCard
     */
    public function __construct(FrontendUsersBankCard $frontendUsersBankCard)
    {
        $this->model = $frontendUsersBankCard;
    }
    /**
     * 用户银行卡列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputData): JsonResponse
    {
        $searchAbleFields = ['bank_name', 'branch', 'username', 'owner_name', 'card_number', 'bank_sign', 'status'];
        $fixedJoin = 1;
        $withTable = ['province', 'city'];
        $withSearchAbleFields = [];
        $data = $contll->generateSearchQuery($this->model, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $contll->msgOut(true, $data);
    }
}
