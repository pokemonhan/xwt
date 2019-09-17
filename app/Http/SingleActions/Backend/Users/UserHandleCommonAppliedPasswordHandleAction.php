<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\Users\UserHandleController;
use Illuminate\Http\JsonResponse;

class UserHandleCommonAppliedPasswordHandleAction
{
    /**
     * 用户登录密码和资金密码公用列表
     * @param  UserHandleController  $contll
     * @return JsonResponse
     */
    public function execute(UserHandleController $contll): JsonResponse
    {
        //main model
        $eloqM = $contll->modelWithNameSpace($contll->withNameSpace);
        $searchAbleFields = ['type', 'status', 'created_at', 'updated_at'];
        //target model to join
        $fixedJoin = 1; //number of joining tables
        $withTable = 'auditFlow';
        $withSearchAbleFields = ['username'];
        $data = $contll->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $contll->msgOut(true, $data);
    }
}
