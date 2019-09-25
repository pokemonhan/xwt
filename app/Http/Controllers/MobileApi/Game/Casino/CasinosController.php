<?php
namespace App\Http\Controllers\MobileApi\Game\Casino;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Game\Casino\CasinoListRequest;
use App\Http\Requests\Frontend\Game\Casino\CasinoSearchRequest;
use App\Http\SingleActions\Frontend\Game\Casino\CasinoGameSearchAction;
use App\Http\SingleActions\Frontend\Game\Casino\CasinoListAction;
use App\Http\SingleActions\Frontend\Game\Casino\CasinoPlatAction;
use Illuminate\Http\JsonResponse;

/**
 * Class CasinosController
 * @package App\Http\Controllers\FrontendApi\Game\Casino
 */
class CasinosController extends FrontendApiMainController
{
    /**
     * 获取娱乐城游戏-列表
     * @param CasinoListRequest $request 参数.
     * @param CasinoListAction  $action  F.
     * @return JsonResponse
     */
    public function casinoList(CasinoListRequest $request, CasinoListAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取娱乐城游戏-游戏平台
     * @param  CasinoPlatAction $action F.
     * @return JsonResponse
     */
    public function casinoPlat(CasinoPlatAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取娱乐城游戏-游戏平台
     * @param CasinoSearchRequest    $request Param.
     * @param CasinoGameSearchAction $action  F.
     * @return JsonResponse
     */
    public function searchGameCasino(CasinoSearchRequest $request, CasinoGameSearchAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
