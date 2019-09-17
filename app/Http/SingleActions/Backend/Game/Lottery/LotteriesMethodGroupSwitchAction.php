<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\Game\Lottery\LotteriesController;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use Exception;
use Illuminate\Http\JsonResponse;

class LotteriesMethodGroupSwitchAction
{
    /**
     * 玩法组开关
     * @param   LotteriesController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(LotteriesController $contll, array $inputDatas): JsonResponse
    {
        $methodGroupIds = LotteryMethod::where('lottery_id', $inputDatas['lottery_id'])
            ->where('method_group', $inputDatas['method_group'])
            ->pluck('id');
        if (empty($methodGroupIds)) {
            return $contll->msgOut(false, [], '101701');
        }
        try {
            $updateDate = ['status' => $inputDatas['status']];
            LotteryMethod::whereIn('id', $methodGroupIds)->update($updateDate);
            $contll->clearMethodCache(); //清理彩种玩法缓存
            LotteryList::lotteryInfoCache(); //更新首页lotteryInfo缓存
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
