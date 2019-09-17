<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\Game\Lottery\LotteriesController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesLotteriesSwitchAction
{
    protected $model;

    /**
     * @param  LotteryList  $lotteryList
     */
    public function __construct(LotteryList $lotteryList)
    {
        $this->model = $lotteryList;
    }

    /**
     * 彩种开关
     * @param   LotteriesController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(LotteriesController $contll, array $inputDatas): JsonResponse
    {
        $lotteriesEloq = $this->model::find($inputDatas['id']);
        $lotteriesEloq->status = $inputDatas['status'];
        $lotteriesEloq->save();
        if ($lotteriesEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $lotteriesEloq->errors()->messages());
        }
        $lotteriesEloq->lotteryInfoCache(); //更新首页lotteryInfo缓存
        $contll->clearMethodCache(); //清理彩种玩法缓存
        return $contll->msgOut(true);
    }
}
