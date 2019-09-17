<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\LotteryTrace;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LotteriesStopTraceAction
{
    protected $model;
    protected $stopAllTraceType = 1;
    protected $stopOneTraceType = 2;

    /**
     * @param  LotteryTraceList  $lotteryTraceList
     */
    public function __construct(LotteryTraceList $lotteryTraceList)
    {
        $this->model = $lotteryTraceList;
    }

    /**
     * 终止追号
     * @param  FrontendApiMainController $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $user = $contll->partnerUser;
        if ($inputDatas['type'] === $this->stopAllTraceType) {
            $traceListsEloqs = $this->model->getUnfinishedTrace($inputDatas['lottery_traces_id'], $user->id);
        } elseif ($inputDatas['type'] === $this->stopOneTraceType) {
            $traceListsEloqs = $this->model::where([['id', $inputDatas['lottery_trace_lists_id']], ['user_id', $user->id]])
                ->whereIn('status', [LotteryTraceList::STATUS_WAITING, LotteryTraceList::STATUS_RUNNING])
                ->get();
        } else {
            return $contll->msgOut(false, [], '100314');
        }
        if ($traceListsEloqs->isEmpty()) {
            return $contll->msgOut(false, [], '100315');
        }
        DB::beginTransaction();
        $canceledNum = 0; //本次取消的期数
        $canceledAmount = 0; //本次取消的金额
        foreach ($traceListsEloqs as $traceListsItem) {
            $issueIsNormal = $this->checkIssueNormal($traceListsItem);
            if ($issueIsNormal['success'] === true) {
                //取消正在追号状态的奖期需要更改project表状态
                if ($traceListsItem->status === LotteryTraceList::STATUS_RUNNING && $traceListsItem->project_serial_number !== null) {
                    $this->saveProject($traceListsItem->project_serial_number);
                }
                $saveTraceList = $this->saveTraceList($traceListsItem);
                if ($saveTraceList['success'] === false) {
                    return $contll->msgOut($saveTraceList['success'], [], '', $saveTraceList['messages']);
                }
                $saveUserAccount = $this->saveUserAccount($user, $traceListsItem);
                if ($saveUserAccount['success'] === false) {
                    return $contll->msgOut($saveUserAccount['success'], [], $saveUserAccount['code'], $saveUserAccount['messages']);
                }
                $canceledNum++; //本次取消的期数
                $canceledAmount += $traceListsItem->total_price; //本次取消的金额
            } else {
                DB::rollBack();
                return $contll->msgOut($issueIsNormal['success'], [], $issueIsNormal['code'], '', $issueIsNormal['placeholder'], $issueIsNormal['substituted']);
            }
        }
        if ($canceledNum > 0) {
            $lotteryTraceEloq = $traceListsEloqs->first()->trace;
            //$this->checkTraceNormal($lotteryTraceEloq); //检查追号是否正常
            $saveTrace = $this->saveLotteryTrace($lotteryTraceEloq, $canceledNum, $canceledAmount, $user->id, $inputDatas);
            if ($saveTrace[0] === false) {
                return $contll->msgOut($saveTrace);
            }
        }
        DB::commit();
        return $contll->msgOut(true);
    }

    //检查奖期是否可以撤单
    public function checkIssueNormal($traceListsItem)
    {
        if (time() > $traceListsItem->issue_end_time) {
            return ['success' => false, 'code' => '100321', 'placeholder' => 'issue', 'substituted' => $traceListsItem->issue];
        } else {
            return ['success' => true];
        }
    }

    public function getAccountParams($userId, $traceListsItem)
    {
        return [
            'user_id' => $userId,
            'amount' => $traceListsItem->total_price,
            'lottery_id' => $traceListsItem->lottery_sign,
            'method_id' => $traceListsItem->method_sign,
            'project_id' => $traceListsItem->project_id ?? null,
            'issue' => $traceListsItem->issue ?? null,
        ];
    }

    public function saveLotteryTrace($lotteryTraceEloq, $canceledNum, $canceledAmount, $userId, $inputDatas)
    {
        $lotteryTraceEloq->canceled_issues += $canceledNum; //累积取消的期数
        $lotteryTraceEloq->canceled_amount += $canceledAmount; //累积取消的金额
        if ($inputDatas['type'] === $this->stopAllTraceType) {
            $finishedTraceListsEloqs = $this->model->getFinishedTraceToRun($inputDatas['lottery_traces_id'], $userId);
            //有半状态正在运行的状态情况下，如果有不对lotteryTrace 做任何改变
            if ($finishedTraceListsEloqs->isEmpty()) {
                $finishedTraceListsEloqs = $this->model->getFinishedTrace($inputDatas['lottery_traces_id'], $userId);
                //如果用户此前有执行过追号其中一条，状态改为STATUS_FINISHED=1 完成，如果没有全部撤销变成 5 用户撤销
                $lotteryTraceEloq->status = $finishedTraceListsEloqs->isEmpty() ?
                LotteryTrace::STATUS_USER_DROPED : LotteryTrace::STATUS_FINISHED;
            }
        } elseif ($inputDatas['type'] === $this->stopOneTraceType) {
            //有半状态正在运行的状态情况下，如果有不对lotteryTrace 做任何改变
            $runningFlag = $this->model->getRuningTrace($inputDatas['lottery_trace_lists_id'], $userId);
            if ($runningFlag->isEmpty()) {
                $unFinishedTraceListsEloqs = $this->model->getUnfinishedTraceAllWating($inputDatas['lottery_trace_lists_id'], $userId);
                //如果是空代表执行结束  否则状态不变
                $lotteryTraceEloq->status = $unFinishedTraceListsEloqs->isEmpty() ?
                LotteryTrace::STATUS_FINISHED : $lotteryTraceEloq->status;
                $lotteryList = $this->model->getUnfinishedTraceAll($inputDatas['lottery_trace_lists_id'], $userId);
                //如果用户总共只有一条追号lottery_trace_list，那么撤销了唯一一条，lottery_trace外部状态应为5 用户撤销
                $lotteryTraceEloq->status = $lotteryList->count() === 1 ?
                LotteryTrace::STATUS_USER_DROPED : $lotteryTraceEloq->status;
            }
        }
        $lotteryTraceEloq->save();
        if ($lotteryTraceEloq->errors()->messages()) {
            DB::rollback();
            return [false, [], '400', $lotteryTraceEloq->errors()->messages()];
        }
        return [true];
    }

    public function saveProject($serialNumber)
    {
        $projectELoq = Project::where('serial_number', $serialNumber)->first();
        if ($projectELoq !== null) {
            if ($projectELoq->status === Project::STATUS_NORMAL) {
                $projectELoq->status = Project::STATUS_DROPED;
                $projectELoq->save();
            }
        }
    }

    public function saveTraceList($traceListsItem)
    {
        $traceListsItem->status = LotteryTraceList::STATUS_USER_STOPED;
        $traceListsItem->cancel_time = Carbon::now()->toDateTimeString();
        $traceListsItem->save();
        if ($traceListsItem->errors()->messages()) {
            DB::rollback();
            return ['success' => false, 'messages' => $traceListsItem->errors()->messages()];
        }
        return ['success' => true];
    }

    public function saveUserAccount($user, $traceListsItem)
    {
        if ($user->account()->exists()) {
            $account = $user->account;
        } else {
            return ['success' => false, 'code' => '100313', 'messages' => ''];
        }
        $params = $this->getAccountParams($user->id, $traceListsItem);
        $resource = $account->operateAccount($params, 'trace_refund'); //帐变处理
        if ($resource !== true) {
            DB::rollBack();
            return ['success' => false, 'code' => '', 'messages' => $resource];
        }
        return ['success' => true];
    }

    /**
     * 如果存在等待追号状态的追号list并且没有正在追号状态的list，则需要把第一个等待状态的追号加入project表
     * @param  LotteryTrace $lotteryTrace
     */
    /*public function checkTraceNormal(LotteryTrace $lotteryTrace)
    {
    $waitingTrace = $lotteryTrace->traceLists->where('status', LotteryTraceList::STATUS_WAITING)->where('issue_end_time', '>', time());
    if ($waitingTrace->isNotEmpty()) {
    $runningTrace = $lotteryTrace->traceLists->where('status', LotteryTraceList::STATUS_RUNNING);
    if ($runningTrace->isEmpty()) {
    $this->insertProject($waitingTrace->first(), $lotteryTrace);
    }
    }
    }*/

    /*public function insertProject($traceList, $lotteryTraceEloq)
{
$projectData = [
'serial_number' => Project::getProjectSerialNumber(),
'user_id' => $traceList->user_id,
'username' => $traceList->username,
'top_id' => $traceList->top_id,
'rid' => $traceList->rid,
'parent_id' => $traceList->parent_id,
'is_tester' => $traceList->is_tester,
'series_id' => $traceList->series_id,
'lottery_sign' => $traceList->lottery_sign,
'method_sign' => $traceList->method_sign,
'method_group' => $traceList->method_group,
'method_name' => $traceList->method_name,
'user_prize_group' => $traceList->user_prize_group,
'bet_prize_group' => $traceList->bet_prize_group,
'mode' => $traceList->mode,
'times' => $traceList->times,
'price' => $traceList->single_price,
'total_cost' => $traceList->total_price,
'bet_number' => $traceList->bet_number,
'issue' => $traceList->issue,
'prize_set' => $traceList->prize_set,
'ip' => $traceList->ip,
'proxy_ip' => $traceList->proxy_ip,
'bet_from' => $traceList->bet_from,
'time_bought' => time(),
'status_flow' => Project::STATUS_FLOW_TRACE_CANCEL,
];
$projectId = Project::create($projectData)->id;
$traceList->project_id = $projectId;
$traceList->project_serial_number = $projectData['serial_number'];
$traceList->status = LotteryTraceList::STATUS_RUNNING;
$traceList->save();
$lotteryTraceEloq->now_issue = $traceList->issue;
}*/
}
