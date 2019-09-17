<?php

namespace App\Http\SingleActions\Mobile\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

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
        $data = self::getTagsCacheData('lottery_popular_lotteries_app');
        if (empty($data)) {
            $data = FrontendLotteryRedirectBetList::webPopularLotteriesCache();
        }
        return $contll->msgOut(true, $data);
    }
}
