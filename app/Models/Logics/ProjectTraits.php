<?php

namespace App\Models\Logics;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryPrizeGroup;
use App\Models\User\FrontendUser;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

trait ProjectTraits
{
    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  LotteryIssue  $currentIssue
     * @param  array  $data
     * @param  array  $inputDatas
     * @param  int  $from  手机端 还是 pc 端
     * @return array
     */
    public static function addProject(
        FrontendUser $user,
        LotteryList $lottery,
        LotteryIssue $currentIssue,
        $data,
        array $inputDatas,
        $from = 1
    ): array {
        $traceFirstMultiple = 1;
        $traceData = [];
        $isTrace = (int) $inputDatas['is_trace'];
        $traceResult = self::traceCompile(
            $lottery,
            $currentIssue,
            $inputDatas,
            $traceFirstMultiple,
            $traceData,
            $isTrace
        );
        if (isset($traceResult['error'])) {
            return $traceResult;
        } else {
            $returnData = self::addProjectsandTraces(
                $user,
                $lottery,
                $currentIssue,
                $data,
                $inputDatas,
                $isTrace,
                $traceFirstMultiple,
                $traceData,
                $traceResult,
                $from
            );
            return $returnData;
        }
    }

    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  LotteryIssue  $currentIssue
     * @param $data
     * @param  array  $inputDatas
     * @param $isTrace
     * @param $traceFirstMultiple
     * @param $traceData
     * @param $traceResult
     * @param  int  $from
     * @return array
     */
    private static function addProjectsandTraces(
        FrontendUser $user,
        LotteryList $lottery,
        LotteryIssue $currentIssue,
        $data,
        array $inputDatas,
        $isTrace,
        $traceFirstMultiple,
        $traceData,
        $traceResult,
        int $from
    ): array {
        $returnData = [];
        foreach ($data as $_item) {
            if ($isTrace !== 1) {
                //如果是追号  不插入project
                $project = self::saveSingleProject(
                    $user,
                    $lottery,
                    $_item,
                    $inputDatas,
                    $isTrace,
                    $traceFirstMultiple,
                    $currentIssue,
                    $from
                );
                $accountType = 'bet_cost';
                $cost = $_item['total_price'];
            } else {
                $accountType = 'trace_cost';
                $cost = self::saveTrace(
                    $user,
                    $lottery,
                    $traceData,
                    $_item,
                    $inputDatas,
                    $from,
                    $traceResult
                );
            }
            $returnData['project'][] = [
                'id' => $project->id ?? null,
                'account_type' => $accountType,
                'cost' => $cost,
                'lottery_id' => $lottery->en_name,
                'method_id' => $_item['method_id'],
                'is_trace' => $isTrace,
            ];
        }
        return $returnData;
    }

    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param  array  $_item
     * @param  array  $inputDatas
     * @param  int  $isTrace
     * @param  int  $traceFirstMultiple
     * @param  LotteryIssue  $currentIssue
     * @param  int  $from
     * @return mixed
     */
    public static function saveSingleProject(
        FrontendUser $user,
        LotteryList $lottery,
        $_item,
        array $inputDatas,
        $isTrace,
        $traceFirstMultiple,
        LotteryIssue $currentIssue,
        $from
    ) {
        $bresult = LotteryPrizeGroup::makePrizeSettingArray(
            $_item['method_id'],
            self::DEFAULT_PRIZE_GROUP,
            $lottery->series_id,
            $aPrizeSettings,
            $aPrizeSettingOfWay,
            $aMaxPrize
        );
        if ($bresult) {
            die('奖金组错误');
        }
        $projectData = self::getSingleProjectData(
            $user,
            $lottery,
            $_item,
            $inputDatas,
            $isTrace,
            $traceFirstMultiple,
            $currentIssue,
            $aPrizeSettingOfWay,
            $from
        );
        return self::create($projectData);
    }

    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param $_item
     * @param  array  $inputDatas
     * @param $isTrace
     * @param $traceFirstMultiple
     * @param  LotteryIssue  $currentIssue
     * @param $aPrizeSettingOfWay
     * @param $from
     * @return array
     */
    private static function getSingleProjectData(
        FrontendUser $user,
        LotteryList $lottery,
        $_item,
        array $inputDatas,
        $isTrace,
        $traceFirstMultiple,
        LotteryIssue $currentIssue,
        $aPrizeSettingOfWay,
        $from
    ): array {
        if ($lottery->serie()->exists()) {
            $subTractPrize = $lottery->serie->price_difference;
        } else {
            $subTractPrize = 0;
        }
        $projectData = [
            'serial_number' => self::getProjectSerialNumber(),
            'user_id' => $user->id,
            'username' => $user->username,
            'top_id' => $user->top_id,
            'rid' => $user->rid,
            'parent_id' => $user->parent_id,
            'is_tester' => $user->is_tester,
            'series_id' => $lottery->series_id,
            'lottery_sign' => $lottery->en_name,
            'method_sign' => $_item['method_id'],
            'method_name' => $_item['method_name'],
            'method_group' => $_item['method_group'],
            'user_prize_group' => $user->prize_group,
            'bet_prize_group' => $_item['prize_group'] - $subTractPrize,
            'prize_set' => json_encode($aPrizeSettingOfWay),
            'mode' => $_item['mode'],
            'times' => $isTrace === 1 && count(
                $inputDatas['trace_issues']
            ) > 0 ? $_item['times'] * $traceFirstMultiple : $_item['times'],
            'price' => $isTrace === 1 && count(
                $inputDatas['trace_issues']
            ) > 0 ? $_item['price'] * $traceFirstMultiple : $_item['price'],
            'total_cost' => $_item['total_price'],
            'bet_number' => $_item['code'],
            'issue' => $currentIssue->issue,
            'ip' => Request::ip(),
            'proxy_ip' => json_encode(Request::ip()),
            'bet_from' => $from,
            'time_bought' => time(),
            //'status_flow' => $isTrace === 1 ? self::STATUS_FLOW_TRACE : self::STATUS_FLOW_NORMAL,
            'challenge_prize' => $_item['challenge_prize'],
            'challenge' => $_item['challenge'],
        ];
        return $projectData;
    }

    /**
     * @return string
     */
    public static function getProjectSerialNumber(): string
    {
        return 'XW' . Str::orderedUuid()->getNodeHex();
    }

    public function setWon(
        $openNumber,
        $sWnNumber,
        $aPrized
    ) {
        $this->compileBonus($aPrized, $sWnNumber, $totalBonus, $totalCount, $arrLevel, $arrBasicMethodId);
        if ($totalCount > 0) {
            $data = [
                'basic_method_id' => implode(',', $arrBasicMethodId),
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'level' => implode(',', $arrLevel), //@todo may be with string to concact
                'is_win' => 1,
                'time_count' => now()->timestamp,
                'status' => self::STATUS_WON,
            ];
            if (($this->challenge === 1) && $totalBonus > $this->challenge_prize) {//单挑模式返奖
                $data['bonus'] = $this->challenge_prize;
                $data['bonus_expected'] = $totalBonus;
            } else {
                $data['bonus'] = $totalBonus;
                $data['bonus_expected'] = $totalBonus;
            }
            try {
                DB::beginTransaction();
                $this->update($data); //@todo maybe only a time update
                DB::commit();
                $this->sendMoney();
                return true;
            } catch (Exception $e) {
                Log::channel('issues')->info($e->getMessage());
                DB::rollBack();
                return $e->getMessage();
            }
        } else {
            return $this->setFail($openNumber, $sWnNumber);
        }
    }

    /**
     * @param $aPrized
     * @param $sWnNumber
     * @param $totalBonus
     * @param $totalCount
     * @param $arrLevel
     * @param $arrBasicMethodId
     */
    private function compileBonus(
        $aPrized,
        $sWnNumber,
        &$totalBonus,
        &$totalCount,
        &$arrLevel,
        &$arrBasicMethodId
    ): void {
        $arrBasicMethodId = [];
        $arrLevel = [];
        $totalBonus = 0;
        $totalCount = 0;
        foreach ($aPrized as $iBasicMethodId => $aPrizeOfBasicMethod) {
            $this->calculateEachPrizeSetting(
                $aPrizeOfBasicMethod,
                $iBasicMethodId,
                $sWnNumber,
                $totalBonus,
                $totalCount,
                $arrLevel,
                $arrBasicMethodId
            );
        }
    }

    /**
     * @param $aPrizeOfBasicMethod
     * @param $iBasicMethodId
     * @param $sWnNumber
     * @param $totalBonus
     * @param $totalCount
     * @param $arrLevel
     * @param $arrBasicMethodId
     */
    private function calculateEachPrizeSetting(
        $aPrizeOfBasicMethod,
        $iBasicMethodId,
        $sWnNumber,
        &$totalBonus,
        &$totalCount,
        &$arrLevel,
        &$arrBasicMethodId
    ): void {
        $aPrizeSet = json_decode($this->prize_set, true);
        foreach ($aPrizeOfBasicMethod as $iLevel => $iCount) {
            $prizeToClaim = $this->getPrizeToClaim($iBasicMethodId, $sWnNumber, $aPrizeSet, $iLevel);
            if ($prizeToClaim !== null) {
                if ($iCount > 0) {
                    $this->calculateBonus($prizeToClaim, $iCount, $totalCount, $totalBonus);
                    $arrLevel[] = $iLevel;
                    $arrBasicMethodId[] = $iBasicMethodId;
                } else {
                    $errorString = 'There have no Count:' . $iBasicMethodId . ' level:' . $iLevel . ' Count:' . $iCount;
                    Log::channel('issues')->info($errorString);
                }
            } else {
                $levelDataNote = 'leveldata' . json_encode($aPrizeOfBasicMethod);
                $errorString = 'There have no prize for  Basic MethodId' . $iBasicMethodId . $levelDataNote;
                Log::channel('issues')->error($errorString);
            }
        }
    }

    /**
     * @param $prizeToClaim
     * @param $iCount
     * @param $totalCount
     * @param $totalBonus
     */
    private function calculateBonus($prizeToClaim, $iCount, &$totalCount, &$totalBonus): void
    {
        $bonus = $this->bet_prize_group * $prizeToClaim / 1800;
        $bonus *= $this->mode * $this->times * $iCount;
        if (pack('f', $this->price) === pack('f', 1.0)) {
            $bonus /= 2;
        }
        $totalCount += $iCount;
        $totalBonus += $bonus;
    }

    /**
     * @param $iBasicMethodId
     * @param $sWnNumber
     * @param $aPrizeSet
     * @param $iLevel
     * @return int|mixed
     */
    private function getPrizeToClaim($iBasicMethodId, $sWnNumber, $aPrizeSet, $iLevel)
    {
        if ($iBasicMethodId === 123) {
            $winExplodedNum = explode(' ', $sWnNumber);
            $tema = end($winExplodedNum);
            if ($tema === '49') {
                $prizeToClaim = 1;
            } else {
                $prizeToClaim = $aPrizeSet[$iBasicMethodId][$iLevel];
            }
        } else {
            $prizeToClaim = $aPrizeSet[$iBasicMethodId][$iLevel];
        }
        return $prizeToClaim;
    }

    /**
     * @param  mixed  $sWnNumber
     * @return string|null
     */
    private function formatWiningNumber(
        $sWnNumber = null
    ):  ? string {
        return is_array($sWnNumber) ? implode('', $sWnNumber) : $sWnNumber;
    }

    public function sendMoney() : void
    {
        $params = [
            'amount' => $this->bonus,
            'frozen_release' => $this->total_cost,
            'user_id' => $this->user_id,
            'project_id' => $this->id,
            'lottery_id' => $this->lottery_sign,
            'method_id' => $this->method_sign,
            'issue' => $this->issue,
        ];
        $account = $this->account;
        DB::beginTransaction();
        try {
            $result = $account->operateAccount($params, 'game_bonus');
            if ($result !== true) {
                Log::info($result);
            } else {
                $result = $this->updateStatusPrizeSend();
            }
        } catch (Exception $e) {
            $result = false;
            $info = '投注-异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();
            Log::channel('calculate-prize')->info($info); //Clog::userBet
        }
        if ($result === true) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }

    /**
     * @return bool
     */
    private function updateStatusPrizeSend(): bool
    {
        $oProject = self::find($this->id);
        if ($oProject->status === self::STATUS_WON) {
            $oProject->status = self::STATUS_PRIZE_SENT;
            $oProject->time_prize = now()->timestamp;
            $oProject->save();
            if (!empty($this->errors()->first())) {
                $result = false;
                $info = '更新状态出错' . json_encode($this->errors()->first(), JSON_PRETTY_PRINT);
                Log::channel('calculate-prize')->info($info);
            } else {
                $result = true;
                Log::channel('calculate-prize')->info('Finished Send Money with bonus');
            }
        } else {
            $result = true;
            Log::channel('calculate-prize')->info('Finished Send Money with release frozen');
        }
        return $result;
    }

    /**
     * @param $openNumber
     * @param $sWnNumber
     * @return bool
     */
    public function setFail(
        $openNumber,
        $sWnNumber = null
    ): bool {
        try {
            DB::beginTransaction();
//            $lockProject = $this->lockForUpdate()->find($this->id);
            $this->status = self::STATUS_LOST;
            $data = [
//                'basic_method_id' => $iBasicMethodId,
                'open_number' => $openNumber,
                'winning_number' => $this->formatWiningNumber($sWnNumber),
                'time_count' => now()->timestamp,
                'status' => self::STATUS_LOST,
            ];
            if ($this->update($data)) {
                DB::commit();
                // $this->sendMoney();
            } else {
                $strError = json_encode($this->errors()->first(), JSON_PRETTY_PRINT);
                Log::channel('issues')->info($strError);
            }
        } catch (Exception $e) {
            Log::channel('issues')->info($e->getMessage());
            DB::rollBack();
            return false;
        }
        return true;
    }
}
