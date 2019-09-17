<?php

namespace App\Models\Logics;

use App\Models\Game\Lottery\LotteryList;
use App\Models\Project;
use App\Models\User\FrontendUser;
use Illuminate\Support\Facades\Request;

trait TraceTraits
{
    /**
     * 获取列表
     * @param  array  $condition
     * @return array
     */
    public static function getList($condition): array
    {
        $query = self::orderBy('id', 'desc');
        if (isset($condition['en_name'])) {
            $query->where('en_name', '=', $condition['en_name']);
        }
        $currentPage = isset($condition['page_index']) ? (int)$condition['page_index'] : 1;
        $pageSize = isset($condition['page_size']) ? (int)$condition['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;
        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();
        return [
            'data' => $menus,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int)ceil($total / $pageSize),
        ];
    }

    /**
     * @param  FrontendUser  $user
     * @param  LotteryList  $lottery
     * @param $traceData
     * @param $_item
     * @param $aPrizeSettingOfWay
     * @param $inputDatas
     * @param $from
     * @return array
     */
    public static function createTraceData(
        FrontendUser $user,
        //Project $project,
        LotteryList $lottery,
        $traceData,
        $_item,
        $aPrizeSettingOfWay,
        $inputDatas,
        $from
    ): array {
        $totalPrice = $_item['total_price'] * count($traceData);
        $traceMainData = [
            'trace_serial_number' => Project::getProjectSerialNumber(),
            'user_id' => $user->id,
            'username' => $user->username,
            'top_id' => $user->top_id,
            'rid' => $user->rid,
            'parent_id' => $user->parent_id,
            'is_tester' => $user->is_tester,
            'series_id' => $lottery->series_id,
            'lottery_sign' => $lottery->en_name,
            'method_sign' => $_item['method_id'],
            'method_group' => $_item['method_group'],
            'method_name' => $_item['method_name'],
            'bet_number' => $_item['code'],
            'user_prize_group' => $user->prize_group,
            'bet_prize_group' => $_item['prize_group'],
            'prize_set' => json_encode($aPrizeSettingOfWay),
            'mode' => $_item['mode'],
            'times' => $_item['times'],
            'single_price' => $_item['price'],
            'total_price' => $totalPrice,
            'win_stop' => $inputDatas['trace_win_stop'],
            'total_issues' => count($traceData),
            'finished_issues' => 0,
            'canceled_issues' => 0,
            'start_issue' => key($traceData),
            //'now_issue' => $project->issue,
            'end_issue' => array_key_last($traceData),
            'stop_issue' => '',
            'issue_process' => json_encode($traceData),
            'add_time' => time(),
            'stop_time' => 0,
            'ip' => Request::ip(),
            'proxy_ip' => json_encode(Request::ip()),
            'bet_from' => $from,
            'challenge_prize' => $_item['challenge_prize'],
            'challenge' => $_item['challenge'],
        ];
        $data['id'] = self::create($traceMainData)->id;
        $data['total_price'] = $totalPrice;
        return $data;
    }
}
