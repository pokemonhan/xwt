<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;

class LotteriesDeleteIssuesAction
{
    protected const TYPE_IDS = 1; //删除类型：ids
    protected const TYPE_DAY = 2; //删除类型：某个彩种一天的所有奖期

    /**
     * 删除奖期
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $lotteryIssueELoq = new LotteryIssue();
        if ($inputDatas['type'] == self::TYPE_IDS) {
            $lotteryIssueELoq->whereIn('id', $inputDatas['id'])->delete();
        } elseif ($inputDatas['type'] == self::TYPE_DAY) {
            $lotteryIssueELoq->where('lottery_id', $inputDatas['lottery'])->where('day', $inputDatas['day'])->delete();
        } else {
            return $contll->msgOut(false, [], '101705');
        }
        if ($lotteryIssueELoq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $lotteryIssueELoq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
