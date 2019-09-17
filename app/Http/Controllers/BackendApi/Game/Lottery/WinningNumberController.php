<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/3/2019
 * Time: 1:39 PM
 */

namespace App\Http\Controllers\BackendApi\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Game\Lottery\WinningNumberSetLotteryNumberRequest;
use App\Http\SingleActions\Backend\Game\Lottery\WinningNumberSetLotteryNumberAction;
use Illuminate\Http\JsonResponse;

class WinningNumberController extends BackEndApiMainController
{
    /**
     * @param  WinningNumberSetLotteryNumberRequest  $request
     * @param  WinningNumberSetLotteryNumberAction  $action
     * @return JsonResponse
     */
    public function setLotteryNumber(
        WinningNumberSetLotteryNumberRequest $request,
        WinningNumberSetLotteryNumberAction $action
    ): JsonResponse {
        $inputDatas = $request->all();
        $headers = $request->header();
        return $action->execute($this, $inputDatas, $headers);
    }
}
