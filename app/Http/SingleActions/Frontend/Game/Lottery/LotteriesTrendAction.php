<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;

class LotteriesTrendAction
{
    /**
     * 彩种走势
     * @param  FrontendApiMainController  $contll
     * @param  array  $inputDatas
     * @param  int  $iType
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas, $iType): JsonResponse
    {
        $sLotteryId = $inputDatas['lottery_id']; // 彩种id
        $iNumType = $inputDatas['num_type']; // 号码位数
        $iCount = $inputDatas['count']; // 要获取的记录数
        $iBeginTime = $inputDatas['begin_time'] ?? null; // 开始时间, utc秒值
        $iEndTime = $inputDatas['end_time'] ?? null; // 结束时间, utc秒值
        if ($iCount > LotteryIssue::$iIssueLimit) {//规定可取的范围
            return $contll->msgOut(false, [], '100319');
        }
        $oTrend = new LotteryIssue();
        switch ($iType) {
            case 2:
                $data = $oTrend->getProbabilityOfOccurrenceByParams(
                    $sLotteryId,
                    $iNumType,
                    $iBeginTime,
                    $iEndTime,
                    $iCount
                );
                break;
            case 1:
            default:
                $data = $oTrend->getTrendDataByParams(
                    $sLotteryId,
                    $iNumType,
                    $iBeginTime,
                    $iEndTime,
                    $iCount
                );
                break;
        }
        return !$data ? $contll->msgOut(false, [], '100324') : $contll->msgOut(true, $data);
    }
}
