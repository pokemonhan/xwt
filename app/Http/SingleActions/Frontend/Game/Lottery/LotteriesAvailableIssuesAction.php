<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesAvailableIssuesAction
{
    /**
     * 游戏-可用奖期
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $lotterySign = $inputDatas['lottery_sign'];
        $lottery = LotteryList::findBySign($lotterySign);
        $canUserInfo = LotteryIssue::getCanBetIssue($lotterySign, $lottery->max_trace_number);
        if ($canUserInfo->count() === 0) {
            LotteryIssue::generateTodayIssue($lotterySign); //生成彩种的今日奖期
        }
        $canBetIssueData = [];
        $currentIssue = [];
        foreach ($canUserInfo as $index => $issue) {
            if ($index <= 0) {
                $currentIssue = [
                    'issue_no' => $issue->issue,
                    'begin_time' => $issue->begin_time,
                    'end_time' => $issue->end_time,
                    'open_time' => $issue->allow_encode_time,
                ];
            }
            $canBetIssueData[] = [
                'issue_no' => $issue->issue,
                'begin_time' => $issue->begin_time,
                'end_time' => $issue->end_time,
                'open_time' => $issue->allow_encode_time,
            ];
        }
        // 上一期
        $_lastIssue = LotteryIssue::getPastIssue($lotterySign);
        $lastIssue = $_lastIssue !== null ? [
            'issue_no' => $_lastIssue->issue,
            'begin_time' => $_lastIssue->begin_time,
            'end_time' => $_lastIssue->end_time,
            'open_time' => $_lastIssue->allow_encode_time,
            'open_code' => $_lastIssue->official_code,
        ] : [];
        $data = [
            'lottery' => $inputDatas['lottery_sign'],
            'issueInfo' => $canBetIssueData,
            'currentIssue' => $currentIssue,
            'lastIssue' => $lastIssue,
            'serverTime' => time(),
        ];
        return $contll->msgOut(true, $data);
    }
}
