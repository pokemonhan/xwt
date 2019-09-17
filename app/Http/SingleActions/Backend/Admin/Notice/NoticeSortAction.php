<?php

namespace App\Http\SingleActions\Backend\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Notice\FrontendMessageNoticesContent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class NoticeSortAction
{
    protected $model;

    /**
     * @param  FrontendMessageNoticesContent  $frontendMessageNoticesContent
     */
    public function __construct(FrontendMessageNoticesContent $frontendMessageNoticesContent)
    {
        $this->model = $frontendMessageNoticesContent;
    }

    /**
     * 公告排序
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            $stationaryData = $this->model;
            //上拉排序
            if ((int) $inputDatas['sort_type'] === 1) {
                $stationaryData = $this->model::find($inputDatas['front_id']);

                if ($stationaryData !== null) {
                    $stationaryData->sort = $inputDatas['front_sort'];
                    $this->model::where('sort', '>=', $inputDatas['front_sort'])->where('sort', '<', $inputDatas['rearways_sort'])->increment('sort');
                } else {
                    DB::rollback();
                    return $contll->msgOut(false, [], '102400');
                }
                //下拉排序
            } elseif ((int) $inputDatas['sort_type'] === 2) {
                $stationaryData = $this->model::find($inputDatas['rearways_id']);

                if ($stationaryData !== null) {
                    $stationaryData->sort = $inputDatas['rearways_sort'];
                    $this->model::where('sort', '>', $inputDatas['front_sort'])->where('sort', '<=', $inputDatas['rearways_sort'])->decrement('sort');
                } else {
                    DB::rollback();
                    return $contll->msgOut(false, [], '102400');
                }
            }

            $stationaryData->save();
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
