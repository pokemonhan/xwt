<?php

namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Fund\FrontendSystemBank;
use Exception;
use Illuminate\Http\JsonResponse;

class BankAddAction
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
     * 添加银行
     * @param  BackEndApiMainController  $contll
     * @param  array  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        try {
            $configure = new $this->model();
            $configure->fill($inputDatas);
            $configure->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
