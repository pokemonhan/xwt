<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\Game\Lottery\LotteriesController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class LotteriesIssueListsAction
{
    protected $model;

    /**
     * @param  LotteryList  $lotteryList
     */
    public function __construct(LotteryList $lotteryList)
    {
        $this->model = $lotteryList;
    }

    /**
     * 获取奖期列表接口。
     * @param   LotteriesController  $contll
     * @return  JsonResponse
     */
    public function execute(LotteriesController $contll): JsonResponse
    {
        $seriesId = $contll->inputs['series_id'] ?? '';
//        {"method":"whereIn","key":"id","value":["cqssc","xjssc","hljssc","zx1fc","txffc"]}
        //        $extraWhereConditions = Arr::wrap(json_decode($this->inputs['extra_where'], true));
        if (!empty($seriesId)) {
            $lotteryEnNames = $this->model::where('series_id', $seriesId)->get(['en_name']);
            $tempLotteryId = [];
            foreach ($lotteryEnNames as $lotteryIthems) {
                $tempLotteryId[] = $lotteryIthems->en_name;
            }
            $contll->inputs['extra_where']['method'] = 'whereIn';
            $contll->inputs['extra_where']['key'] = 'lottery_id';
            $contll->inputs['extra_where']['value'] = $tempLotteryId;
        }
        $searchFieldArr = ['issue']; //存在指定搜索字段  不插入time_condtions条件
        $isExistField = arr::has($contll->inputs, $searchFieldArr);
        if ($isExistField === false) {
            if (!isset($contll->inputs['time_condtions'])) {
                $endTimeStamp = time(); // 不存在时间段搜索时，默认返回现在还未结束的奖期
                //选定彩种并选择了展示已过期的期数时  获取结束时间>那一期的奖期
                if (isset($contll->inputs['lottery_id'], $contll->inputs['previous_number'])) {
                    $lotteryIssueEloq = LotteryIssue::getPastIssue(
                        $contll->inputs['lottery_id'],
                        $contll->inputs['previous_number']
                    );
                    $endTimeStamp = $lotteryIssueEloq->end_time ?? time();
                }
                $timeCondtions = '[["end_time",">",' . $endTimeStamp . ']]';
                $contll->inputs['time_condtions'] = $timeCondtions;
            }
        }
        $eloqM = $contll->modelWithNameSpace($contll->lotteryIssueEloq);
        $searchAbleFields = ['lottery_id', 'issue'];
        $fixedJoin = 1;
        $withTable = 'lottery';
        $orderFields = 'begin_time';
        $orderFlow = 'asc';
        $issueList = $contll->generateSearchQuery(
            $eloqM,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            [],
            $orderFields,
            $orderFlow
        );
        $this->insertCodeExample($issueList); //插入开奖号码例子
        return $contll->msgOut(true, $issueList);
    }

    /**
     * 插入开奖号码例子
     * @param  LengthAwarePaginator $issues
     * @return void
     */
    public function insertCodeExample(LengthAwarePaginator $issues): void
    {
        $serieList = LotterySerie::getList();
        foreach ($issues as $issueItem) {
            $codeLength = $issueItem->lottery->code_length ?? null;
            $validCode = $issueItem->lottery->valid_code ?? null;
            $lotteryType = $issueItem->lottery->lottery_type ?? null;
            $splitter = $serieList[$issueItem->lottery->series_id]['encode_splitter'] ?? null;
            $codeExample = LotteryIssue::getOpenNumber(
                $codeLength,
                $validCode,
                $lotteryType,
                $splitter,
                $issueItem->lottery->series_id
            );
            $issueItem->code_example = $codeExample;
        }
    }
}
