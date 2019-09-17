<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Http\JsonResponse;

class LotteriesAllLotteriesListAction
{
    /**
     * 全部的彩种列表
     * @param   BackEndApiMainController  $contll
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $lotterySerieELoqs = LotterySerie::select('series_name', 'title')->get();
        $datas = [];
        foreach ($lotterySerieELoqs as $key => $serieELoq) {
            $datas[$key][$serieELoq->series_name] = $serieELoq->series_name;
            $datas[$key][$serieELoq->title] = $serieELoq->title;
            $datas[$key]['list'] = $serieELoq->lotteries;
        }
        return $contll->msgOut(true, $datas);
    }
}
