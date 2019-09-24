<?php
namespace App\Http\Controllers\BackendApi\Casino\Game\Plats;

use App\Http\Controllers\BackendApi\Casino\CasinoApiMainController;
use App\Http\SingleActions\Backend\Casino\Game\Plats\GetGamePlatsAction;

/**
 * Class GamePlatController
 * @package App\Http\Controllers\BackendApi\Game\Lottery
 */
class GamePlatController extends CasinoApiMainController
{
    /**
     * 获取 游戏平台
     * @param  GetGamePlatsAction $action Func.
     * @return boolean
     */
    public function seriesLists(GetGamePlatsAction $action)
    {
        return $action->execute($this);
    }
}
