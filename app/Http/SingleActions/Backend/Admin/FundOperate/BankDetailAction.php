<?php

namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Fund\FrontendSystemBank;
use Illuminate\Http\JsonResponse;

class BankDetailAction
{
    protected $model;

    /**
     * @param  FrontendSystemBank  $frontendSystemBank
     */
    public function __construct(FrontendSystemBank $frontendSystemBank)
    {
        $this->model = $frontendSystemBank;
    }

    /**
     * 银行列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['title', 'code', 'pay_type', 'status'];
        $orderFields = 'id';
        $orderFlow = 'asc';
        $banksDatas = $contll->generateSearchQuery(
            $this->model,
            $searchAbleFields,
            0,
            null,
            [],
            $orderFields,
            $orderFlow
        );
        return $contll->msgOut(true, $banksDatas);
    }
}
