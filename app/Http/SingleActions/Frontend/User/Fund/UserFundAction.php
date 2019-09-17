<?php

namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\Fund\FrontendUsersAccountsReport;
use Illuminate\Http\JsonResponse;

class UserFundAction
{

    protected $model;

    /**
     * @param  FrontendUsersAccountsReport  $frontendUsersAccountsReport
     */
    public function __construct(FrontendUsersAccountsReport $frontendUsersAccountsReport)
    {
        $this->model = $frontendUsersAccountsReport;
    }

    /**
     * 用户账变列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $contll->inputs['user_id'] = $contll->partnerUser->id;
        $searchAbleFields = ['user_id', 'type_sign', 'lottery_id', 'method_id', 'project_id', 'issue'];
        $fixedJoin = 2; //number of joining tables
        $withTable = ['gameMethods:method_id,method_name','lottery:cn_name,en_name'];
        $withSearchAbleFields = [];
        $orderFields = 'created_at';
        $orderFlow = 'desc';
        $data = $contll->generateSearchQuery(
            $this->model,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            $withSearchAbleFields,
            $orderFields,
            $orderFlow
        );
        return $contll->msgOut(true, $data);
    }
}
