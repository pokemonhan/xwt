<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class LotteriesListsAction
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
     * 获取彩种接口
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $lotteries = $this->model::where('series_id', $inputDatas['series_id'])
            ->with('issueRule:id,lottery_id,lottery_name,begin_time,end_time,issue_seconds,first_time,adjust_time,encode_time,issue_count,status')
            ->get()
            ->toArray();
        return $contll->msgOut(true, $lotteries);
    }
}
