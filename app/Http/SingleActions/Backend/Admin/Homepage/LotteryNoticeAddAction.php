<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryNoticeList;
use Illuminate\Http\JsonResponse;

class LotteryNoticeAddAction
{
    /**
     * 添加开奖公告的彩种
     * @param   BackEndApiMainController  $contll
     * @param   array                     $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $maxSort = FrontendLotteryNoticeList::select('sort')->max('sort');
        $sort = ++$maxSort;
        $addData = [
            'lotteries_id' => $inputDatas['lotteries_id'],
            'cn_name' => $inputDatas['cn_name'],
            'status' => $inputDatas['status'],
            'sort' => $sort,
        ];
        $lotteryNoticeELoq = new FrontendLotteryNoticeList();
        $lotteryNoticeELoq->fill($addData);
        $lotteryNoticeELoq->save();
        if ($lotteryNoticeELoq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $lotteryNoticeELoq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
