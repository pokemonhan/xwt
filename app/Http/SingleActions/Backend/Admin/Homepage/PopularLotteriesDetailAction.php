<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use Exception;
use Illuminate\Http\JsonResponse;

class PopularLotteriesDetailAction
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
     * 热门彩票列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $lotterieEloqs = $this->model::select(
            'id',
            'lotteries_id',
            'sort'
        )->with('lotteries:id,cn_name,icon_path')->orderBy('sort', 'asc')->get();
        $datas = [];
        foreach ($lotterieEloqs as $lotterieItem) {
            $data = [
                'id' => $lotterieItem->id,
                'lotteries_id' => $lotterieItem->lotteries_id,
                'cn_name' => $lotterieItem->lotteries->cn_name ?? null,
                'pic_path' => $lotterieItem->lotteries->icon_path ?? null,
                'sort' => $lotterieItem->sort,
            ];
            $datas[] = $data;
        }
        return $contll->msgOut(true, $datas);
    }
}
