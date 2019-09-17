<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\Game\Lottery\LotteriesController;
use App\Lib\BaseCache;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Http\JsonResponse;

class LotteriesMethodListsAction
{
    use BaseCache;

    protected $model;

    /**
     * @param  LotteryList  $lotteryList
     */
    public function __construct(LotteryList $lotteryList)
    {
        $this->model = $lotteryList;
    }

    /**
     * 获取玩法结果。
     * @param   LotteriesController  $contll
     * @return  JsonResponse
     */
    public function execute(LotteriesController $contll): JsonResponse
    {
        $redisKey = $contll->redisKey;
        $data = self::getTagsCacheData($redisKey);
        if (empty($data)) {
            $seriesEloq = LotterySerie::get();
            foreach ($seriesEloq as $seriesIthem) {
                $lottery = $seriesIthem->lotteries; //->where('status',1)
                $seriesId = $seriesIthem->series_name;
                foreach ($lottery as $litems) {
                    $data = $this->getMethodData($litems, $seriesId, $data);
                }
            }
            self::saveTagsCacheData($redisKey, $data);
        }
        return $contll->msgOut(true, $data);
    }

    /**
     * 组装玩法组和玩法行data
     * @param  int $lotteryId   [彩种]
     * @param  int $methodGroup [玩法组]
     * @param  int $status      [开启状态]
     * @param  int $methodRow   [玩法行]
     * @return array  $dataArr
     */
    public function methodData($lotteryId, $methodGroup, $status, $methodRow = null): array
    {
        $dataArr = [
            'lottery_id' => $lotteryId,
            'method_group' => $methodGroup,
            'status' => $status, //玩法行下是否存在开启状态的玩法
        ];
        if ($methodRow !== null) {
            $dataArr['method_row'] = $methodRow;
        }
        return $dataArr;
    }

    public function getMethodData($litems, $seriesId, &$data): array
    {
        $lotteyArr = collect($litems->toArray())
            ->only(['id', 'cn_name', 'status']);
        $currentLotteryId = $litems->en_name;
        $data[$seriesId][$currentLotteryId]['data'] = $lotteyArr;
        $data[$seriesId][$currentLotteryId]['child'] = [];
        $methodGrops = $litems->methodGroups;
        foreach ($methodGrops as $mgItems) {
            $curentMethodGroup = $mgItems->method_group;
            $methodGroupBool = $mgItems->where('lottery_id', $currentLotteryId)
                ->where('method_group', $curentMethodGroup)
                ->where('status', 1)
                ->exists();
            $methodGroupstatus = $methodGroupBool ? LotteryMethod::STATUS_OPEN : LotteryMethod::STATUS_CLOSE;
            //玩法组 data
            $methodGroup = $this->methodData($currentLotteryId, $curentMethodGroup, $methodGroupstatus);
            //$data 插入玩法组data
            $data[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['data'] = $methodGroup;
            $data[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'] = [];
            $methodRows = $mgItems->methodRows;
            foreach ($methodRows as $mrItems) {
                $currentMethodRow = $mrItems->method_row;
                $methodRowBool = $mrItems->where('lottery_id', $currentLotteryId)
                    ->where('method_group', $curentMethodGroup)
                    ->where('method_row', $currentMethodRow)
                    ->where('status', 1)
                    ->exists();
                $methodRowstatus = $methodRowBool ? LotteryMethod::STATUS_OPEN : LotteryMethod::STATUS_CLOSE;
                //玩法行 data
                $methodRow = $this->methodData(
                    $currentLotteryId,
                    $curentMethodGroup,
                    $methodRowstatus,
                    $currentMethodRow
                );
                //$data 插入玩法行data
                $data[$seriesId][$currentLotteryId]['child']
                [$curentMethodGroup]['child'][$mrItems->method_row]['data'] = $methodRow;
                //玩法data
                $methodData = LotteryMethod::where('lottery_id', $currentLotteryId)
                    ->where('method_group', $curentMethodGroup)
                    ->where('method_row', $currentMethodRow)
                    ->get();
                $data[$seriesId][$currentLotteryId]['child']
                [$curentMethodGroup]['child'][$mrItems->method_row]['child'] = $methodData;
            }
        }
        return $data;
    }
}
