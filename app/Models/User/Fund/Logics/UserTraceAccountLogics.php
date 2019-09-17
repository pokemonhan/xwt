<?php

namespace App\Models\User\Fund\Logics;

trait UserTraceAccountLogics
{
	/**
     * 开始追号时先解冻金额
     * @param  LotteryTraceList $traceList
     */
    public static function traceThaw($traceList)
    {
        $user = $traceList->user;
        if ($user) {
            $account = $user->account;
            if ($account) {
                $params = [
                    'user_id' => $traceList->user_id,
                    'amount' => $traceList->total_price,
                    'lottery_id' =>$traceList->lottery_sign,
                    'method_id' => $traceList->method_sign,
                    'issue' => $traceList->issue
                ];
                $result = $account->operateAccount($params, 'trace_un_frozen');
                if ($result !== true) {
                    Log::channel('trace')->info('追号list：' . $traceList->id . '解冻失败(' . $result . ')');
                }
            } else {
                Log::channel('trace')->info('用户：' . $user->id . '资金表不存在');
            }
        } else {
            Log::channel('trace')->info('追号list：' . $traceList->id . '用户信息不存在');
        }
    }

    /**
     * 追号生成project下注帐变
     * @param  project $project
     */
    public static function betDeduction($project)
    {
        $account = $project->account;
        if ($account) {
            $params = [
                'user_id' => $project->user_id,
                'amount' => $project->total_cost,
                'lottery_id' => $project->lottery_sign,
                'method_id' => $project->method_sign,
                'project_id' => $project->id,
                'issue' => $project->issue,
            ];
            return $account->operateAccount($params, 'bet_cost');
        } else {
            Log::channel('trace')->info('用户：' . $user->id . '资金表不存在');
        }
    }

    /**
     * 追号中奖停止后的追号返款
     * @param  Project $oProject
     * @param  float $amount
     * @param  LotteryTrace $trace
     */
    public static function traceWinStopAccount($oProject, $amount, $trace)
    {
        $account = $oProject->account;
        if ($account) {
            $params = [
                'user_id' => $oProject->user_id,
                'amount' => $amount,
                'lottery_id' => $trace->lottery_sign,
                'method_id' => $trace->method_sign,
            ];
            $result = $account->operateAccount($params, 'trace_refund');
            if ($result !== true) {
                Log::channel('trace')->info('追号：' . $trace->id . '中奖返款失败(' . $result . ')');
            }
        } else {
            Log::channel('trace')->info('用户：' . $oProject->user_id. '资金表不存在');
        }
    }
}
