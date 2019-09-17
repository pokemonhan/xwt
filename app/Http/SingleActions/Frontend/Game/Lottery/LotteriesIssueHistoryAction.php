<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

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
        $issuesEloq = LotteryIssue::where([
            ['lottery_id', $inputDatas['lottery_sign']],
            ['status_encode', 1],
        ])->orderBy('begin_time', 'desc')->limit($inputDatas['count'])->get();
        $data = [];
        foreach ($issuesEloq as $issueEloq) {
            $data[] = [
                'issue_no' => $issueEloq->issue,
                'code' => $issueEloq->official_code,
            ];
        }
        return $contll->msgOut(true, $data);
    }
}
