<?php

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\BaseCache;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use Illuminate\Http\JsonResponse;

class HompagePopularLotteriesAction
{
    use BaseCache;

    /**
     * 热门彩票一
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $datas = self::getTagsCacheData('lottery_popular_lotteries_web');
        if (empty($datas)) {
            $datas = FrontendLotteryRedirectBetList::webPopularLotteriesCache();
        }
        return $contll->msgOut(true, $datas);
    }
}
