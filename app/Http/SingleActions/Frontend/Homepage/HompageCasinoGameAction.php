<?php
namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\BaseCache;
use App\Models\Casino\CasinoGameList;
use Illuminate\Http\JsonResponse;

/**
 * Class HompageCasinoGameAction
 * @package App\Http\SingleActions\Frontend\Homepage
 */
class HompageCasinoGameAction
{
    use BaseCache;

    /**
     * 首页推荐娱乐城
     * @param  FrontendApiMainController $contll F.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $datas = self::getTagsCacheData('casino_popular_web');
        if (empty($datas)) {
            $datas = CasinoGameList::webCasinoCache();
        }
        return $contll->msgOut(true, $datas);
    }
}
