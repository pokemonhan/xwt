<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryNoticeList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LotteryNoticeSortAction
{
    /**
     * 排序开奖公告的彩种
     * @param   BackEndApiMainController  $contll
     * @param   array                     $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        //上拉排序
        $frontendLotteryNoticeList = new FrontendLotteryNoticeList();
        if ($inputDatas['sort_type'] == 1) {
            $stationaryData = FrontendLotteryNoticeList::find($inputDatas['front_id']);
            $stationaryData->sort = $inputDatas['front_sort'];
            $frontendLotteryNoticeList->sortIncrement($inputDatas['front_sort'], $inputDatas['rearways_sort']);
        } elseif ($inputDatas['sort_type'] == 2) {
            //下拉排序
            $stationaryData = FrontendLotteryNoticeList::find($inputDatas['rearways_id']);
            $stationaryData->sort = $inputDatas['rearways_sort'];
            $frontendLotteryNoticeList->sortDecrement($inputDatas['front_sort'], $inputDatas['rearways_sort']);
        } else {
            return $contll->msgOut(false);
        }
        $stationaryData->save();
        if ($stationaryData->errors()->messages()) {
            DB::rollback();
            return $contll->msgOut(false, [], '400', $stationaryData->errors()->messages());
        }
        DB::commit();
        return $contll->msgOut(true);
    }
}
