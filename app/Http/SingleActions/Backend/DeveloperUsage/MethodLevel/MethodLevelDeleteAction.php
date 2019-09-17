<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\MethodLevel;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use Illuminate\Http\JsonResponse;

class MethodLevelDeleteAction
{
    protected $model;

    /**
     * @param  LotteryMethodsWaysLevel  $lotteryMethodsWaysLevel
     */
    public function __construct(LotteryMethodsWaysLevel $lotteryMethodsWaysLevel)
    {
        $this->model = $lotteryMethodsWaysLevel;
    }

    /**
     * 删除玩法等级
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $methodLevelEloq = $this->model::find($inputDatas['id']);
        $methodLevelEloq->delete();
        if ($methodLevelEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $methodLevelEloq->errors()->messages());
        }
        $this->model::methodLevelDetail(1); //更新缓存
        return $contll->msgOut(true);
    }
}
