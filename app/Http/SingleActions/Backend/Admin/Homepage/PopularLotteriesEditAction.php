<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use Illuminate\Http\JsonResponse;

class PopularLotteriesEditAction
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
     * 编辑热门彩票
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::find($inputDatas['id']);
        $pastDataEloq->lotteries_sign = $inputDatas['lotteries_sign'];
        $pastDataEloq->lotteries_id = $inputDatas['lotteries_id'];
        $pastDataEloq->save();
        if ($pastDataEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $pastDataEloq->errors()->messages());
        }
        $this->model::updatePopularLotteriesCache(); //更新首页热门彩票缓存
        return $contll->msgOut(true);
    }
}
