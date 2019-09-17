<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\Admin\Homepage\HomepageBannerController;
use App\Models\Admin\Homepage\FrontendPageBanner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HomepageBannerSortAction
{
    protected $model;

    /**
     * @param  FrontendPageBanner  $frontendPageBanner
     */
    public function __construct(FrontendPageBanner $frontendPageBanner)
    {
        $this->model = $frontendPageBanner;
    }

    /**
     * 首页轮播图排序
     * @param  HomepageBannerController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(HomepageBannerController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //上拉排序
            if ($inputDatas['sort_type'] == 1) {
                $stationaryData = $this->model::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->model::where('sort', '>=', $inputDatas['front_sort'])
                    ->where('sort', '<', $inputDatas['rearways_sort'])
                    ->increment('sort');
                //下拉排序
            } elseif ($inputDatas['sort_type'] == 2) {
                $stationaryData = $this->model::find($inputDatas['rearways_id']);
                $stationaryData->sort = $inputDatas['rearways_sort'];
                $this->model::where('sort', '>', $inputDatas['front_sort'])
                    ->where('sort', '<=', $inputDatas['rearways_sort'])
                    ->decrement('sort');
            } else {
                return $contll->msgOut(false);
            }
            $stationaryData->save();
            DB::commit();
            //清除首页banner缓存
            $contll->deleteCache();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
