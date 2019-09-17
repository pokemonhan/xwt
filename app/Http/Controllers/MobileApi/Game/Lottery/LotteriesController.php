<?php

namespace App\Http\Controllers\MobileApi\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesAvailableIssuesRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesBetRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesCancelBetRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesLastIssuesRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesProjectHistoryRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesStopTraceRequest;
use App\Http\Requests\Mobile\Game\Lottery\LotteriesIssueHistoryRequest;
use App\Http\Requests\Mobile\Game\Lottery\LotteriesTraceIssueListRequest;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesAvailableIssuesAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesBetAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesCancelBetAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesLastIssuesAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesLotteryInfoAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesStopTraceAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesTracesHistoryAction;
use App\Http\SingleActions\Mobile\Game\Lottery\LotteriesIssueHistoryAction;
use App\Http\SingleActions\Mobile\Game\Lottery\LotterieslotteryCenterAction;
use App\Http\SingleActions\Mobile\Game\Lottery\LotteriesLotteryListAction;
use App\Http\SingleActions\Mobile\Game\Lottery\LotteriesProjectHistoryAction;
use App\Http\SingleActions\Mobile\Game\Lottery\LotteriesTraceIssueListAction;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesTrendRequest;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesTrendAction;
use Illuminate\Http\JsonResponse;

class LotteriesController extends FrontendApiMainController
{
    /**
     * 获取彩票列表
     * @param   LotteriesLotteryListAction  $action
     * @return  JsonResponse
     */
    public function lotteryList(LotteriesLotteryListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 游戏 彩种详情
     * @param   LotteriesLotteryInfoAction  $action
     * @return  JsonResponse
     */
    public function lotteryInfo(LotteriesLotteryInfoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 历史奖期
     * @param   LotteriesIssueHistoryRequest  $request
     * @param   LotteriesIssueHistoryAction   $action
     * @return  JsonResponse
     */
    public function issueHistory(
        LotteriesIssueHistoryRequest $request,
        LotteriesIssueHistoryAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 7. 游戏-可用奖期
     * @param   LotteriesAvailableIssuesRequest  $request
     * @param   LotteriesAvailableIssuesAction   $action
     * @return  JsonResponse
     */
    public function availableIssues(
        LotteriesAvailableIssuesRequest $request,
        LotteriesAvailableIssuesAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 游戏-下注历史
     * @param   LotteriesProjectHistoryRequest  $request
     * @param   LotteriesProjectHistoryAction   $action
     * @return  JsonResponse
     */
    public function projectHistory(
        LotteriesProjectHistoryRequest $request,
        LotteriesProjectHistoryAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 游戏-追号历史
     * @param   LotteriesTracesHistoryAction   $action
     * @return  JsonResponse
     */
    public function tracesHistory(
        LotteriesTracesHistoryAction $action
    ): JsonResponse {
        return $action->execute($this);
    }

    /**
     * 游戏-投注
     * @param   LotteriesBetRequest  $request
     * @param   LotteriesBetAction   $action
     * @return  JsonResponse
     */
    public function lotteryBet(LotteriesBetRequest $request, LotteriesBetAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 开奖中心
     * @param   LotterieslotteryCenterAction $action
     * @return  JsonResponse
     */
    public function lotteryCenter(LotterieslotteryCenterAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 终止追号
     * @param  LotteriesStopTraceRequest $request
     * @param  LotteriesStopTraceAction  $action
     * @return JsonResponse
     */
    public function stopTrace(LotteriesStopTraceRequest $request, LotteriesStopTraceAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 投注撤单
     * @param  LotteriesCancelBetRequest $request
     * @param  LotteriesCancelBetAction  $action
     * @return JsonResponse
     */
    public function cancelBet(LotteriesCancelBetRequest $request, LotteriesCancelBetAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取彩种上期的奖期
     * @param  LotteriesLastIssuesRequest $request
     * @param  LotteriesLastIssuesAction  $action
     * @return JsonResponse
     */
    public function lastIssue(LotteriesLastIssuesRequest $request, LotteriesLastIssuesAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 彩种可追号的奖期列表
     * @param  LotteriesTraceIssueListRequest $request
     * @param  LotteriesTraceIssueListAction  $action
     * @return JsonResponse
     */
    public function traceIssueList(
        LotteriesTraceIssueListRequest $request,
        LotteriesTraceIssueListAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取走势图接口
     * @param  LotteriesTrendRequest $request
     * @param  LotteriesTrendAction  $action
     * @return JsonResponse
     */
    public function trend(LotteriesTrendRequest $request, LotteriesTrendAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas,$iType = 1);
    }
}
