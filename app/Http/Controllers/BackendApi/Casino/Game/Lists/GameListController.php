<?php
namespace App\Http\Controllers\BackendApi\Casino\Game\Lists;

use App\Http\Controllers\BackendApi\Casino\CasinoApiMainController;
use App\Http\SingleActions\Backend\Casino\Game\Lists\GetGameListsAction;

/**
 * Class GameListController
 * @package App\Http\Controllers\BackendApi\Casino\Game\Lists
 */
class GameListController extends CasinoApiMainController
{
    /**
     * 获取 游戏列表
     * @param  GetGameListsAction $action Func.
     * @return JsonResponse
     */
    public function getGame(GetGameListsAction $action)
    {
        return $action->execute($this);
    }
}
