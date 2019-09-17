<?php

namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\BackendApi\Admin\Activity\ActivityInfosController;
use App\Lib\BaseCache;
use App\Models\Admin\Activity\FrontendActivityContent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ActivityInfosSortAction
{
    use BaseCache;

    protected $model;

    /**
     * @param  FrontendActivityContent  $frontendActivityContent
     */
    public function __construct(FrontendActivityContent $frontendActivityContent)
    {
        $this->model = $frontendActivityContent;
    }

    /**
     * 活动排序
     * @param  ActivityInfosController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(ActivityInfosController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //上拉排序
            if ((int) $inputDatas['sort_type'] === 1) {
                $stationaryData = $this->model::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->model::where([
                    ['type', $inputDatas['type']],
                    ['sort', '>=', $inputDatas['front_sort']],
                    ['sort', '<', $inputDatas['rearways_sort']]
                ])
                    ->increment('sort');
                //下拉排序
            } elseif ((int) $inputDatas['sort_type'] === 2) {
                $stationaryData = $this->model::find($inputDatas['rearways_id']);
                $stationaryData->sort = $inputDatas['rearways_sort'];
                $this->model::where([
                    ['type', $inputDatas['type']],
                    ['sort', '>', $inputDatas['front_sort']],
                    ['sort', '<=', $inputDatas['rearways_sort']]
                ])
                    ->decrement('sort');
            } else {
                return $contll->msgOut(false);
            }
            $stationaryData->save();
            DB::commit();
            self::deleteTagsCache($contll->redisKey); //删除前台活动缓存
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
