<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableList;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PopularMethodsMethodsListAction
{
    /**
     * 添加热门玩法时选择的玩法列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $lotteryIds = FrontendLotteryFnfBetableMethod::groupBy('lottery_id')->pluck('lottery_id')->toArray();
        //取出开启状态的彩票
        $lotterys = LotteryList::whereIn('en_name', $lotteryIds)
            ->where('status', 1)
            ->orderBy('id', 'asc')->pluck('cn_name')->toArray();
        $data = [];
        foreach ($lotterys as $lottery) {
            $methodIds = FrontendLotteryFnfBetableMethod::where('lottery_name', $lottery)->pluck('id');
            $methods = LotteryMethod::select(
                'lottery_id',
                'lottery_name',
                'id as method_id',
                'method_name',
                'status'
            )->whereIn('id', $methodIds)->get()->toArray();
            $data[$lottery] = $methods;
        }
        return $contll->msgOut(true, $data);
    }
}
