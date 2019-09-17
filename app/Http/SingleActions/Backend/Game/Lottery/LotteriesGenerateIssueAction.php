<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Events\IssueGenerateEvent;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesGenerateIssueAction
{
    /**
     * 生成奖期
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $generateIssueData = $inputDatas;
        if ($inputDatas['lottery_id'] === '*') {
            $lottery = LotteryList::generateIssueLotterys();
        } else {
            $lottery = (array) $inputDatas['lottery_id'];
        }
        foreach ($lottery as $lotterySign) {
            $generateIssueData['lottery_id'] = $lotterySign;
            event(new IssueGenerateEvent($generateIssueData));
        }
        return $contll->msgOut(true);
    }
}
