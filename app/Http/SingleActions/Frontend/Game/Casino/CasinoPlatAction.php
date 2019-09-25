<?php
namespace App\Http\SingleActions\Frontend\Game\Casino;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;

/**
 * Class CasinoPlatAction
 * @package App\Http\SingleActions\Frontend\Game\Casino
 */
class CasinoPlatAction
{
    /**
     * 游戏-平台
     * @param  FrontendApiMainController $contll F.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $datas = config('casino.categories');
        return $contll->msgOut(true, $datas);
    }
}
