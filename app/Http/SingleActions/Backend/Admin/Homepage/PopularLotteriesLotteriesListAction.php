<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class PopularLotteriesLotteriesListAction
{

    /**
     * 选择的彩种列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $lotteries = LotteryList::select('id', 'cn_name', 'en_name')->get();
        return $contll->msgOut(true, $lotteries);
    }
}
