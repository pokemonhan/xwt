<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\BaseCache;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use App\Models\DeveloperUsage\TaskScheduling\CronJob;
use App\Models\Game\Lottery\LotteryIssueRule;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LotteriesAddAction
{
    use BaseCache;

    /**
     * 添加彩种
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        $lotteryDatas = $inputDatas['lottery'];
        $lotteryDatas['icon_path'] = '/' . $lotteryDatas['icon_path'];
        unset($lotteryDatas['icon_name']);
        $lotteryEloq = new LotteryList();
        $lotteryEloq->fill($lotteryDatas);
        $lotteryEloq->save();
        if ($lotteryEloq->errors()->messages()) {
            DB::rollback();
            return $contll->msgOut(false, [], '400', $lotteryEloq->errors()->messages());
        }
        $methodELoq = new LotteryMethod();
        $insertStatus = $methodELoq->cloneLotteryMethods($lotteryEloq); //克隆彩种玩法
        if ($insertStatus['success'] === false) {
            DB::rollback();
            return $contll->msgOut(false, [], '400', $insertStatus['message']);
        }
        if ($inputDatas['lottery']['auto_open'] == 1) {
            $createData = CronJob::insertCronJob($inputDatas['cron']); //自开彩种 自动开奖任务
            if ($createData['success'] === false) {
                DB::rollback();
                return $contll->msgOut(false, [], '400', $createData['message']);
            }
        }
        foreach ($inputDatas['issue_rule'] as $issueRuleData) {
            $issueRuleELoq = new LotteryIssueRule();
            $issueRuleELoq->fill($issueRuleData);
            $issueRuleELoq->save();
            if ($issueRuleELoq->errors()->messages()) {
                DB::rollback();
                return $contll->msgOut(false, [], '400', $issueRuleELoq->errors()->messages());
            }
        }
        DB::commit();
        self::deleteCachePic($inputDatas['lottery']['icon_name']); //从定时清理的缓存图片中移除上传成功的图片
        $lotteryEloq->lotteryInfoCache(); //更新首页lotteryInfo缓存
        LotteryList::updateAllLotteryByCache(); //更新所有彩票&玩法缓存
        FrontendLotteryRedirectBetList::updatePopularLotteriesCache(); //更新首页热门彩票缓存
        return $contll->msgOut(true);
    }
}
