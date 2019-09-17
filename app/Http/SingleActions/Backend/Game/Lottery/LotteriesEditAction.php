<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use App\Models\Game\Lottery\LotteryIssueRule;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Lib\BaseCache;

class LotteriesEditAction
{
    use BaseCache;

    /**
     * 编辑彩种
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        $lotteryEloq = LotteryList::find($inputDatas['lottery']['id']);
        $lotteryData = $inputDatas['lottery'];
        if (isset($lotteryData['icon_name'])) {
            $iconName = Arr::pull($lotteryData, 'icon_name');
            $pastIcon = $lotteryEloq->icon_path;
            $lotteryData['icon_path'] = '/' . $lotteryData['icon_path'];
        }
        //编辑彩种
        $contll->editAssignment($lotteryEloq, $lotteryData);
        $lotteryEloq->save();
        if ($lotteryEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $lotteryEloq->errors()->messages());
        }
        //编辑奖期规则
        foreach ($inputDatas['issue_rule'] as $issueRuleData) {
            $issueRuleEloq = LotteryIssueRule::find($issueRuleData['id']);
            $contll->editAssignment($issueRuleEloq, $issueRuleData);
            $issueRuleEloq->save();
            if ($issueRuleEloq->errors()->messages()) {
                DB::rollback();
                return $contll->msgOut(false, [], '400', $issueRuleEloq->errors()->messages());
            }
        }
        DB::commit();
        if (isset($iconName)) {
            self::deleteCachePic($iconName); //从定时清理的缓存图片中移除上传成功的图片
        }
        if (isset($pastIcon)) {
            ImageArrange::deletePic(substr($pastIcon, 1));
        }
        $lotteryEloq->lotteryInfoCache(); //更新首页lotteryInfo缓存
        LotteryList::updateAllLotteryByCache(); //更新所有彩票&玩法缓存
        FrontendLotteryRedirectBetList::updatePopularLotteriesCache(); //更新首页热门彩票缓存
        return $contll->msgOut(true);
    }
}
