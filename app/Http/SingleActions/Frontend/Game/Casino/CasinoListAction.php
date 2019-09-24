<?php
namespace App\Http\SingleActions\Frontend\Game\Casino;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Casino\CasinoGameList;
use Illuminate\Http\JsonResponse;

/**
 * Class CasinoListAction
 * @package App\Http\SingleActions\Frontend\Game\Casino
 */
class CasinoListAction
{
    /**
     * 游戏-列表
     * @param  FrontendApiMainController $contll     F.
     * @param  array                     $inputDatas P.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        /**
         * 通过游戏类型 / 平台 获取所有游戏
         */
        $page     = $inputDatas['page'] ?? 1;
        $pageSize = $inputDatas['pageSize'] ?? 20;
        $offset   = ($page - 1) * $pageSize;
        $platCode = $inputDatas['platCode'] ?? 'pt';
        $categorie = $inputDatas['categorie'] ?? 'e-game';

        $data = CasinoGameList::where('category', $categorie)->where('main_game_plat_code', $platCode)->skip($offset)->take($pageSize)->get();
        return $contll->msgOut(true, $data);
    }
}
