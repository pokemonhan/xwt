<?php

namespace App\Http\SingleActions\Mobile\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesTraceIssueListAction
{
    /**
     * 彩种可追号的奖期列表
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $lottery = LotteryList::findBySign($inputDatas['lottery_sign']);
        $canTraceIssueEloq = LotteryIssue::getCanTraceIssue($lottery->en_name, $lottery->max_trace_number);
        $canTraceIssueData = [];
        foreach ($canTraceIssueEloq as $issue) {
            $canTraceIssueData[] = [
                'issue_no' => $issue->issue,
                'begin_time' => $issue->begin_time,
                'end_time' => $issue->end_time,
                'open_time' => $issue->allow_encode_time,
            ];
        }
        return $contll->msgOut(true, $canTraceIssueData);
    }
}
