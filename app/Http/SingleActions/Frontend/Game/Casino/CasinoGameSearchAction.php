<?php
namespace App\Http\SingleActions\Frontend\Game\Casino;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\BaseCache;
use App\Models\Casino\CasinoGameList;
use Illuminate\Http\JsonResponse;

/**
 * Class CasinoGameSearchAction
 * @package App\Http\SingleActions\Frontend\Game\Casino
 */
class CasinoGameSearchAction
{
    use BaseCache;
    /**
     * 游戏-平台
     * @param  FrontendApiMainController $contll     C.
     * @param  array                     $inputDatas Param.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $platCode   = $inputDatas['platCode'] ?? 'pt';
        $categorie  = $inputDatas['categorie'] ?? 'e-game';
        $gameCode   = $inputDatas['gameCode'] ?? '';

        $data = CasinoGameList::where('main_game_plat_code', $platCode)->where('category', $categorie)->where('cn_name', $gameCode)->get();

        return $contll->msgOut(true, $data);
    }
}
