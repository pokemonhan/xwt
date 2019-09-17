<?php

namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UsersRechargeHistorie;
use Illuminate\Http\JsonResponse;

class UserRechargeListAction
{
    protected $model;

    /**
     * @param  UsersRechargeHistorie  $usersRechargeHistorie
     */
    public function __construct(UsersRechargeHistorie $usersRechargeHistorie)
    {
        $this->model = $usersRechargeHistorie;
    }

    /**
     * 用户充值列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $contll->inputs['user_id'] = $contll->partnerUser->id;
        $searchAbleFields = ['user_id', 'company_order_num', 'created_at', 'amount'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $data = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
        return $contll->msgOut(true, $data);
    }
}
