<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use Illuminate\Http\JsonResponse;

class PopularLotteriesAddAction
{
    protected $model;

    /**
     * @param  FrontendLotteryRedirectBetList  $frontendLotteryRedirectBetList
     */
    public function __construct(FrontendLotteryRedirectBetList $frontendLotteryRedirectBetList)
    {
        $this->model = $frontendLotteryRedirectBetList;
    }

    /**
     * 添加热门彩票一
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
            'lotteries_sign' => $inputDatas['lotteries_sign'],
            'sort' => $sort,
        ];
        $popularLotteriesEloq = new $this->model;
        $popularLotteriesEloq->fill($addData);
        $popularLotteriesEloq->save();
        if ($popularLotteriesEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $popularLotteriesEloq->errors()->messages());
        }
        $this->model::updatePopularLotteriesCache(); //更新首页热门彩票缓存
        return $contll->msgOut(true);
    }
}
