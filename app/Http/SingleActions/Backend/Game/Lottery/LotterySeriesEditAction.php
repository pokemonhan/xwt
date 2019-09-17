<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Http\JsonResponse;

class LotterySeriesEditAction
{
    /**
     * 彩种系列 编辑
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $inputDatas['encode_splitter'] = $inputDatas['encode_splitter'] === 'space' ? ' ' : $inputDatas['encode_splitter'];
        $lotterySerieEloq = LotterySerie::find($inputDatas['id']);
        $contll->editAssignment($lotterySerieEloq, $inputDatas);
        $lotterySerieEloq->save();
        if ($lotterySerieEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $lotterySerieEloq->errors()->messages());
        }
        LotterySerie::updateSerieCache(); //更新彩种系列缓存
        return $contll->msgOut(true);
    }
}
