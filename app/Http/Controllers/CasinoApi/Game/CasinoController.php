<?php
namespace App\Http\Controllers\CasinoApi\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Http\Requests\Casino\Game\GetBalanceRequest;
use App\Http\Requests\Casino\Game\JoinGameRequest;
use App\Http\Requests\Casino\Game\KickRequest;
use App\Http\Requests\Casino\Game\TransferInRequest;
use App\Http\Requests\Casino\Game\TransferToRequest;
use App\Http\SingleActions\Casino\Game\GetBalanceAction;
use App\Http\SingleActions\Casino\Game\JoinGameAction;
use App\Http\SingleActions\Casino\Game\KickAction;
use App\Http\SingleActions\Casino\Game\TransferInAction;
use App\Http\SingleActions\Casino\Game\TransferToAction;

class CasinoController extends CasinoApiMainController
{
    /**
     * 进入游戏
     * @param  JoinGameAction   $action
     * @param  JoinGameRequest  $request
     * @return JsonResponse
     */
    public function joinGame(JoinGameRequest $request, JoinGameAction $action)
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 查询余额
     * @param  GetBalanceAction     $action
     * @param  GetBalanceRequest    $request
     * @return JsonResponse
     */
    public function getBalance(GetBalanceRequest $request, GetBalanceAction $action)
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 转入游戏平台
     * @param  TransferInAction     $action
     * @param  TransferInRequest    $request
     * @return JsonResponse
     */
    public function transferIn(TransferInRequest $request, TransferInAction $action)
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 转出游戏平台
     * @param  TransferToAction     $action
     * @param  TransferToRequest    $request
     * @return JsonResponse
     */
    public function transferTo(TransferToRequest $request, TransferToAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 踢线
     * @param  KickAction  $action
     * @param  KickRequest  $request
     * @return JsonResponse
     */
    public function kick(KickRequest $request, KickAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
