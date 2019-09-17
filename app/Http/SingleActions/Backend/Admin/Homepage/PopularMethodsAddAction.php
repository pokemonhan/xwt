<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableList;
use Exception;
use Illuminate\Http\JsonResponse;

class PopularMethodsAddAction
{
    protected $model;

    /**
     * @param  FrontendLotteryFnfBetableList  $frontendLotteryFnfBetableList
     */
    public function __construct(FrontendLotteryFnfBetableList $frontendLotteryFnfBetableList)
    {
        $this->model = $frontendLotteryFnfBetableList;
    }

    /**
     * 热门彩票二 添加热门彩票
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        //sort
        $maxSort = $this->model::select('sort')->max('sort');
        $sort = ++$maxSort;
        $addData = [
            'lotteries_id' => $inputDatas['lotteries_id'],
            'method_id' => $inputDatas['method_id'],
            'sort' => $sort,
        ];
        $popularLotteriesEloq = new $this->model;
        $popularLotteriesEloq->fill($addData);
        $popularLotteriesEloq->save();
        if ($popularLotteriesEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $popularLotteriesEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
