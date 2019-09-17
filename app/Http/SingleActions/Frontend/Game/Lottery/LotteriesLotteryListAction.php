<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;
use App\Models\Game\Lottery\LotterySerie;

class LotteriesLotteryListAction
{
    /**
     * 获取彩票列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $lotteries = LotteryList::with(['issueRule:lottery_id,begin_time,end_time'])
            ->where('status', 1)->get([
                'id',
                'cn_name as name',
                'en_name',
                'series_id',
                'min_times',
                'max_times',
                'valid_modes',
                'min_prize_group',
                'max_prize_group',
                'max_trace_number',
                'day_issue',
            ]);
        $seriesConfig = LotterySerie::getList();
        $data = [];
        foreach ($lotteries as $lottery) {
            if (!isset($data[$lottery->series_id])) {
                $data[$lottery->series_id] = [
                    'name' => $seriesConfig[$lottery->series_id]['title'],
                    'sign' => $lottery->series_id,
                    'encode_splitter' => $seriesConfig[$lottery->series_id]['encode_splitter'],
                    'price_difference' => $seriesConfig[$lottery->series_id]['price_difference'],
                    'list' => [],
                ];
            }
            $data[$lottery->series_id]['list'][] = [
                'number_id' => $lottery->id,
                'id' => $lottery->en_name,
                'name' => $lottery->name,
                'min_times' => $lottery->min_times,
                'max_times' => $lottery->max_times,
                'valid_modes' => $lottery->valid_modes,
                'min_prize_group' => $lottery->min_prize_group,
                'max_prize_group' => $lottery->max_prize_group,
                'max_trace_number' => $lottery->max_trace_number,
                'day_issue' => $lottery->day_issue,
                'begin_time' => $lottery->issueRule->first()['begin_time'],
                'end_time' => $lottery->issueRule->first()['end_time'],
            ];
        }
        return $contll->msgOut(true, $data);
    }
}
