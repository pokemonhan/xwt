<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/3/2019
 * Time: 1:41 PM
 */

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class WinningNumberSetLotteryNumberAction
{
    public function execute(BackEndApiMainController $contll, $inputDatas = [], $headers = []): JsonResponse
    {
        Log::channel('open-center')->info('Inputs are ' . json_encode($inputDatas, JSON_PRETTY_PRINT));
        Log::channel('open-center')->info('Headers are ' . json_encode($headers, JSON_PRETTY_PRINT));
        //####################接收字段需要验证####################
        $issue = $inputDatas['issue']; //奖期
        $code = $inputDatas['number']; //开奖号码
        $lotterySign = $inputDatas['lottery']; //推过来的彩种sign 需要匹配到平台的彩种sign
        $signList = Config::get('game.lottery.sign');
        $lotteryId = $signList[$lotterySign] ?? null; //匹配平台彩种sign
        $closeLottery = Config::get('game.lottery.close');
        if (in_array($lotteryId, $closeLottery)) {
            return $contll->msgOut(false);
        }
        LotteryIssue::encode($lotteryId, $issue, $code);
        //####################返回信息需要处理####################
        return $contll->msgOut(true);
    }
}
