<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\MethodLevel;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use Illuminate\Http\JsonResponse;

class MethodLevelEditAction
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
     * 编辑玩法等级
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $methodLevelEloq = $this->model::find($inputDatas['id']);
        //检查玩法等级
        $isExistMethodLevel = $this->model::where([
            ['method_id', $methodLevelEloq->method_id],
            ['level', $inputDatas['level']],
            ['id', '!=', $inputDatas['id']],
        ])->exists();
        if ($isExistMethodLevel === true) {
            return $contll->msgOut(false, [], '102200');
        }
        $contll->editAssignment($methodLevelEloq, $inputDatas);
        $methodLevelEloq->save();
        if ($methodLevelEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $methodLevelEloq->errors()->messages());
        }
        $this->model::methodLevelDetail(1); //更新缓存
        return $contll->msgOut(true);
    }
}
