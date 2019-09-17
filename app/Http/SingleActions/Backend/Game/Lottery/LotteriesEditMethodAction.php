<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Http\JsonResponse;

class LotteriesEditMethodAction
{
    /**
     * 编辑玩法
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $lotteryMethodEloq = LotteryMethod::find($inputDatas['id']);
        $lotteryMethodEloq->total = $inputDatas['total'];
        $lotteryMethodEloq->save();
        if ($lotteryMethodEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $lotteryMethodEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
