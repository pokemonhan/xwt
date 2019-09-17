<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Jobs\Lottery\Encode\IssueEncoder;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;

class LotteriesInputCodeAction
{
    /**
     * 奖期录号
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $issueEloq = LotteryIssue::where([
            ['issue', $inputDatas['issue']],
            ['lottery_id', $inputDatas['lottery_id']],
        ])->first();
        if ($issueEloq === null) {
            return $contll->msgOut(false, [], '101703');
        }
        if ($issueEloq->official_code !== null) {
            return $contll->msgOut(false, [], '101704');
        }
        if ($issueEloq->end_time > time()) {
            return $contll->msgOut(false, [], '101706');
        }
        LotteryIssue::encode($inputDatas['lottery_id'], $inputDatas['issue'], $inputDatas['code']);
        return $contll->msgOut(true);
    }
}
