<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Project;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersAccount;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Game\Lottery\LotteryMethod;

class LotteriesBetAction
{
    public const NO_BETING = 2;
    public const NO_USE_MONEY = 4;
    /**
     * @var FrontendApiMainController
     */
    private $contll;

    public function __construct()
    {
        $this->contll = new FrontendApiMainController();
    }


    /**
     * 游戏-投注
     * @param  FrontendApiMainController  $contll
     * @param  array  $inputDatas
     * @return JsonResponse
     * @throws Exception
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $from = $this->deviceEnd($contll);
        $user = $contll->currentAuth->user();
        $lotterySign = $inputDatas['lottery_sign'];
        $lottery = LotteryList::getLottery($lotterySign);
        $_totalCost = 0;
        // 获取当前奖期 @todo 判断过期 还是其他期
        $currentIssue = LotteryIssue::getCurrentIssue($lottery->en_name);
        if (!$currentIssue) {
            return $contll->msgOut(false, [], '100310');
        }
        // 初次解析
        $betDetail = $this->getBetDetail($inputDatas, $lottery, $user, $account,$_totalCost);
        if (!is_array($betDetail)) {
            return $betDetail;
        }
        $checkTotalCostStatus = $this->checkTotalCost($inputDatas, $_totalCost);
        if ($checkTotalCostStatus !== true) {
            return $checkTotalCostStatus;
        }
        // 奖期和追号
        /*if ($currentIssue->issue != $traceData[0]) {
        return $this->msgOut(false, [], '', '对不起, 奖期已过期!');
        }*/
        return $this->compileProject($user, $account, $lottery, $currentIssue, $betDetail, $inputDatas, $from);
    }

    /**
     * @param  array  $inputDatas
     * @param  LotteryList  $lottery
     * @param  FrontendUser  $user
     * @param  mixed  $account
     * @param  float|int  $_totalCost
     * @return array|bool|JsonResponse
     */
    private function getBetDetail(array $inputDatas, LotteryList $lottery, FrontendUser $user, &$account, &$_totalCost)
    {
        $betDetail = [];
        $prizeGroup = 0;
        foreach ($inputDatas['balls'] as $item) {
            // 检查玩法是否存在&状态是否开启
            $checkMethod = LotteryMethod::checkMethod($lottery->en_name, $item['method_id']);
            if ($checkMethod === false) {
                return $this->contll->msgOut(false, [], '100323');
            }
            $methodId = $item['method_id'];
            $method = $lottery->getMethod($methodId);//已在 Request 层验证过 无需再次验证
            $oMethod = $method['object']; // 玩法 - 对象
            $prizeGroup = (int)$item['prize_group'];// 奖金组 - 游戏
            $times = (int)$item['times'];// 倍数
            $expandResult = $this->expandUpdateIthem($oMethod, $item);//展开更新数据与校验
            if ($expandResult !== true) {
                return $expandResult;
            }
            if (!$lottery->isValidPrizeGroup($prizeGroup)) {
                return $this->contll->msgOut(false, [], '100302', '', 'prizeGroup', $prizeGroup);
            }
            if (!$lottery->isValidTimes($times)) {
                return $this->contll->msgOut(false, [], '100305', '', 'times', $times);
            }
            // 单价计算
            $singleCost = $this->getSingleCost($item, $mode, $times);//模式，倍数
            $_totalCost = $this->getTotalCost($inputDatas, $singleCost, $_totalCost);//总价计算
            $checkFloatStatus = $this->checkSingleCost($item, $singleCost);
            if ($checkFloatStatus !== true) {
                return $checkFloatStatus;
            }
            $betDetail[] = [
                'method_id' => $methodId,
                'method_group' => $item['method_group'],
                'method_name' => $method['method_name'],
                'mode' => $mode,
                'prize_group' => $prizeGroup,
                'times' => $times,
                'price' => $item['price'],
                'total_price' => $singleCost,
                'code' => $item['codes'],
                'challenge_prize'=>$item['challenge_prize'],
                'challenge'=>$item['challenge'],
            ];
        }
        $userCriteriasCheckStatus = $this->userCriteriasCheck($user, $prizeGroup, $_totalCost, $account);
        if ($userCriteriasCheckStatus !== true) {
            return $userCriteriasCheckStatus;
        }
        return $betDetail;
    }

    /**
     * @param  mixed  $oMethod
     * @param  array  $item
     * @return bool|JsonResponse
     */
    public function expandUpdateIthem($oMethod, &$item)
    {
        /*$project['codes'] = $oMethod->resolve($oMethod->parse64($item['codes']));// 转换格式
            $string = 'method_id' . $methodId . 'before' . $item['codes'] . 'after' . $project['codes'];
            Log::info($string);*/
        if ($oMethod->supportExpand) {
            $position = [];
            if (isset($item['position'])) {
                $position = (array)$item['position'];
            }
            if (!$oMethod->checkPos($position)) {
                return $this->contll->msgOut(false, [], '100300', '', 'methodName', $oMethod->name);
            } else {
                $expands = $oMethod->expand($item['codes'], $position);
                foreach ($expands as $expand) {
                    $item['method_id'] = $expand['method_id'];
                    $item['codes'] = $expand['codes'];
                    $item['count'] = $expand['count'];
                    $item['cost'] = $item['mode'] * $item['times'] * $item['price'];
                }
            }
        }
        return true;
    }

    /**
     * @param  FrontendUser  $user
     * @param  int  $prizeGroup
     * @param  float|int  $_totalCost
     * @param  mixed  $account
     * @return bool|JsonResponse
     */
    private function userCriteriasCheck(FrontendUser $user, $prizeGroup, $_totalCost, &$account)
    {
        //不准投注的账户
        if ($user->frozen_type === self::NO_BETING) {
            return $this->contll->msgOut(false, [], '100317');
        }
        // 奖金组 - 用户
        if ($user->prize_group < $prizeGroup) {
            return $this->contll->msgOut(false, [], '100303', '', 'prizeGroup', $prizeGroup);
        }
        //不可资金操作的账户
        if ($user->frozen_type === self::NO_USE_MONEY) {
            return $this->contll->msgOut(false, [], '100318');
        }
        if ($user->account()->exists()) {
            $account = $user->account;
            if ($account->balance < $_totalCost) {
                return $this->contll->msgOut(false, [], '100312');
            }
        } else {
            return $this->contll->msgOut(false, [], '100313');
        }
        return true;
    }

    /**
     * @param  array  $inputDatas
     * @param  float|int  $_totalCost
     * @return bool|JsonResponse
     */
    private function checkTotalCost(array $inputDatas, $_totalCost)
    {
        $fTotalCost = (float)$_totalCost;
        $fInputTotalCost = (float)$inputDatas['total_cost'];
        if (pack('f', $fTotalCost) !== pack('f', $fInputTotalCost)) {//因为前端有多种传送 所以不能用三等
            return $this->contll->msgOut(false, [], '100307');
        } else {
            return true;
        }
    }

    /**
     * @param  array  $item
     * @param  float|int  $singleCost
     * @return bool|JsonResponse
     */
    private function checkSingleCost(array $item, $singleCost)
    {
        $float = (float)$item['cost'];
        if (pack('f', $singleCost) !== pack('f', $float)) { //因为前端有多种传送 所以不能用三等
            return $this->contll->msgOut(false, [], '100306');
        } else {
            return true;
        }
    }

    /**
     * @param  array  $item
     * @param  float|int  $mode
     * @param  float|int  $times
     * @return float|int
     */
    private function getSingleCost(array $item, &$mode, &$times)
    {
        $mode = (float)$item['mode'];// 模式
        $times = (int)$item['times'];// 倍数
        return $mode * $times * $item['price'] * $item['count'];
    }

    /**
     * @param  array  $inputDatas
     * @param  float|int  $singleCost
     * @return float|int
     */
    private function getTotalCost(array $inputDatas, $singleCost, &$_totalCost)
    {
        // $_totalCost = 0;
        if ((int)$inputDatas['is_trace'] === 1) {
//            $i = 0;
            foreach ($inputDatas['trace_issues'] as $traceMultiple) {
                /*if ($i++ < 1) {
                    continue;
                }*/
                $_totalCost += $traceMultiple * $singleCost;
            }
        } else {
            $_totalCost += $singleCost;
        }
        return $_totalCost;
    }

    /**
     * @param  FrontendUser  $user
     * @param  FrontendUsersAccount  $account
     * @param  LotteryList  $lottery
     * @param  LotteryIssue  $currentIssue
     * @param  array  $betDetail
     * @param  array  $inputDatas
     * @param  int  $from
     * @return JsonResponse
     */
    private function compileProject(
        FrontendUser $user,
        FrontendUsersAccount $account,
        LotteryList $lottery,
        LotteryIssue $currentIssue,
        array $betDetail,
        array $inputDatas,
        int $from
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $data = Project::addProject($user, $lottery, $currentIssue, $betDetail, $inputDatas, $from);
            if (isset($data['error'])) {
                return $this->contll->msgOut(false, [], $data['error']);
            }
            foreach ($data['project'] as $item) {
                $params = [
                    'user_id' => $user->id,
                    'amount' => $item['cost'],
                    'lottery_id' => $item['lottery_id'],
                    'method_id' => $item['method_id'],
                    'project_id' => $item['id'] ?? null,
                    'issue' => $currentIssue->issue,
                ];
                $result = $account->operateAccount($params, $item['account_type']);
                if ($result !== true) {
                    DB::rollBack();
                    return $this->contll->msgOut(false, [], '', $result);
                }
            }
            //如果是追号投注,全部生成了等待状态的list,需要去进行追号
            if ((int) $inputDatas['is_trace'] === 1) {
                $executeTrace = LotteryIssue::handleTraceWithCurrentIssue($lottery);
                if ($executeTrace !== true) {
                    DB::rollBack();
                    return $this->contll->msgOut(false, [], '100322');
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('投注-异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine()); //Clog::userBet
            return $this->contll->msgOut(
                false,
                [],
                '',
                '对不起, '.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine()
            );
        }
        return $this->contll->msgOut(true, $data);
    }

    /**
     * @param  FrontendApiMainController  $contll
     * @return int
     */
    private function deviceEnd(FrontendApiMainController $contll): int
    {
        if ($contll->userAgent->isDesktop()) {
            $from = Project::FROM_DESKTOP;
        } elseif ($contll->userAgent->isMobile()) {
            $from = Project::FROM_MOBILE;
        } else {
            $from = Project::FROM_OTHER;
        }
        return $from;
    }
}
