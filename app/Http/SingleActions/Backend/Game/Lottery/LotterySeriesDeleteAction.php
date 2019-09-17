<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Http\JsonResponse;

class LotterySeriesDeleteAction
{
    /**
     * 彩种系列 删除
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $lotterySerieEloq = LotterySerie::find($inputDatas['id']);
        $lotteryNum = $lotterySerieEloq->lotteries->count(); //系列下存在彩种  不可删除
        if ($lotteryNum !== 0) {
            return $contll->msgOut(false, [], '100400');
        }
        $lotterySerieEloq->delete();
        if ($lotterySerieEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $lotterySerieEloq->errors()->messages());
        }
        LotterySerie::updateSerieCache(); //更新彩种系列缓存
        return $contll->msgOut(true);
    }
}
