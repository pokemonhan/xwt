<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryNoticeList;
use Illuminate\Http\JsonResponse;

class LotteryNoticeEditAction
{
    /**
     * 编辑开奖公告的彩种
     * @param   BackEndApiMainController  $contll
     * @param   array                     $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastEloq = FrontendLotteryNoticeList::find($inputDatas['id']);
        $pastEloq->lotteries_id = $inputDatas['lotteries_id'] ?? $pastEloq->lotteries_id;
        $pastEloq->cn_name = $inputDatas['cn_name'] ?? $pastEloq->cn_name;
        $pastEloq->status = $inputDatas['status'] ?? $pastEloq->status;
        $pastEloq->save();
        if ($pastEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $pastEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
