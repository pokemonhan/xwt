<?php
namespace App\Http\SingleActions\Frontend\Game\Casino;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\BaseCache;
use App\Models\Casino\CasinoGamePlatform;
use Illuminate\Http\JsonResponse;

/**
 * Class CasinoPlatAction
 * @package App\Http\SingleActions\Frontend\Game\Casino
 */
class CasinoPlatAction
{
    use BaseCache;

    /**
     * 游戏-平台
     * @param  FrontendApiMainController $contll F.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $casino_game_plats_key = 'casino_game_plats';
        $datas = self::getTagsCacheData($casino_game_plats_key);
        if (empty($datas)) {
            $datas = CasinoGamePlatform::get();
            $datas = self::saveTagsCacheData($casino_game_plats_key, $datas);
        }
        return $contll->msgOut(true, $datas);
    }
}
