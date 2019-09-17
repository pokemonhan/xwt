<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LotteriesDeleteAction
{
    /**
     * 删除彩种
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        $lotteryEloq = LotteryList::find($inputDatas['id']);
        $pastIcon = $lotteryEloq->icon_path;
        $issueRuleEloq = $lotteryEloq->issueRule;
        $gameMethodsEloq = $lotteryEloq->gameMethods;
        $lotteryEloq->delete();
        if ($lotteryEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $lotteryEloq->errors()->messages());
        }
        foreach ($issueRuleEloq as $issueRuleItem) {
            $issueRuleItem->delete();
            if ($issueRuleItem->errors()->messages()) {
                DB::rollback();
                return $contll->msgOut(false, [], '', $issueRuleItem->errors()->messages());
            }
        }
        foreach ($gameMethodsEloq as $gameMethodItem) {
            $gameMethodItem->delete();
            if ($gameMethodItem->errors()->messages()) {
                DB::rollback();
                return $contll->msgOut(false, [], '', $gameMethodItem->errors()->messages());
            }
        }
        DB::commit();
        $imageObj = new ImageArrange();
        $imageObj->deletePic(substr($pastIcon, 1));
        $lotteryEloq->lotteryInfoCache(); //更新首页lotteryInfo缓存
        LotteryList::updateAllLotteryByCache(); //更新所有彩票&玩法缓存
        FrontendLotteryRedirectBetList::updatePopularLotteriesCache(); //更新首页热门彩票缓存
        return $contll->msgOut(true);
    }
}
