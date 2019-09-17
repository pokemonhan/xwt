<?php

namespace App\Http\SingleActions\Mobile\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;

class LotteriesIssueHistoryAction
{
    /**
     * 历史奖期
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $contll->inputs['status_encode'] = LotteryIssue::ENCODED;
        $lotteryIssueEloq = new LotteryIssue();
        $searchAbleFields = ['lottery_id', 'status_encode'];
        $orderFields = 'begin_time';
        $orderFlow = 'desc';
        $data = $contll->generateSearchQuery(
            $lotteryIssueEloq,
            $searchAbleFields,
            0,
            null,
            [],
            $orderFields,
            $orderFlow
        );
        return $contll->msgOut(true, $data);
    }
}
