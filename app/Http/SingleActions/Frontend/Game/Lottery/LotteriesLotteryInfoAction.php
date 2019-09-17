<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\BaseCache;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesLotteryInfoAction
{
    use BaseCache;

    /**
     * 游戏 彩种详情
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $redisKey = 'frontend_lottery_lotteryInfo';
        $data = self::getTagsCacheData($redisKey);
        if (empty($data)) {
            $data = LotteryList::lotteryInfoCache();
        }
        return $contll->msgOut(true, $data);
    }
}
