<?php

namespace App\Http\Controllers\BackendApi\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Game\Lottery\LotterySeriesAddRequest;
use App\Http\Requests\Backend\Game\Lottery\LotterySeriesDeleteRequest;
use App\Http\Requests\Backend\Game\Lottery\LotterySeriesEditRequest;
use App\Http\SingleActions\Backend\Game\Lottery\LotterySeriesAddAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotterySeriesDeleteAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotterySeriesDetailAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotterySeriesEditAction;
use Illuminate\Http\JsonResponse;

class LotterySeriesController extends BackEndApiMainController
{
    /**
     * 彩种系列 列表
     * @param  LotterySeriesDetailAction $action
     * @return JsonResponse
     */
    public function detail(LotterySeriesDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 彩种系列 添加
     * @param  LotterySeriesAddRequest $request
     * @param  LotterySeriesAddAction  $action
     * @return JsonResponse
     */
    public function add(LotterySeriesAddRequest $request, LotterySeriesAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 彩种系列 编辑
     * @param  LotterySeriesEditRequest $request
     * @param  LotterySeriesEditAction  $action
     * @return JsonResponse
     */
    public function edit(LotterySeriesEditRequest $request, LotterySeriesEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 彩种系列 删除
     * @param  LotterySeriesDeleteRequest $request
     * @param  LotterySeriesDeleteAction  $action
     * @return JsonResponse
     */
    public function delete(LotterySeriesDeleteRequest $request, LotterySeriesDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
