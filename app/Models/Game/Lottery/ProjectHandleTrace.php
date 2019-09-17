<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 9/5/2019
 * Time: 4:32 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\LotteryTrace;
use App\Models\User\FrontendUser;
use Illuminate\Support\Arr;

trait ProjectHandleTrace
{
    /**
     * @param  LotteryList  $lottery
     * @param  LotteryIssue  $currentIssue
     * @param  array  $inputDatas
     * @param $traceFirstMultiple
     * @param $traceData
     * @return mixed
     */
    protected static function traceCompile(
        LotteryList $lottery,
        LotteryIssue $currentIssue,
        array $inputDatas,
        &$traceFirstMultiple,
        &$traceData,
        $isTrace
    ) {
        if ($isTrace === 1 && count($inputDatas['trace_issues']) > 1) {
            // 追号期号
            $arrTraceKeys = array_keys($inputDatas['trace_issues']);
            $traceDataCollection = $lottery->checkTraceData($arrTraceKeys);
            $traceFirstMultiple = Arr::first($inputDatas['trace_issues']);
            // $traceData = array_slice($inputDatas['trace_issues'], 1, null, true);
            $traceData = $inputDatas['trace_issues'];
            if (count($arrTraceKeys) !== $traceDataCollection->count()) {
                $traceError['error'] = '100309';
                return $traceError;
            } else {
                return $traceDataCollection;
            }
        } elseif ($isTrace === 0) {
            // 投注期号是否正确
            if ($currentIssue->issue !== (string)key($inputDatas['trace_issues'])) {
                $traceError['error'] = '100310';
                return $traceError;
            }
        }
    }

    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  array  $traceData
     * @param  array  $_item
     * @param  array  $inputDatas
     * @param  int  $from
     */
    public static function saveTrace(
        FrontendUser $user,
        LotteryList $lottery,
        $traceData,
        $_item,
        $inputDatas,
        $from,
        $traceResult
    ) {
        LotteryPrizeGroup::makePrizeSettingArray(
            $_item['method_id'],
            self::DEFAULT_PRIZE_GROUP,
            $lottery->series_id,
            $aPrizeSettings,
            $aPrizeSettingOfWay,
            $aMaxPrize
        );
        // 保存主追号
        $trace = LotteryTrace::createTraceData(
            $user,
            $lottery,
            $traceData,
            $_item,
            $aPrizeSettingOfWay,
            $inputDatas,
            $from
        );
        // 保存追号列表
        LotteryTraceList::createTraceListData(
            $trace['id'],
            $traceData,
            $user,
            $lottery,
            $_item,
            $aPrizeSettingOfWay,
            $from,
            $traceResult
        );
        return $trace['total_price'];
    }
}
