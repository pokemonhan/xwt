<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\Admin\Homepage\HomepageBannerController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Homepage\FrontendPageBanner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HomepageBannerDeleteAction
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
     * 删除首页轮播图
     * @param  HomepageBannerController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(HomepageBannerController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::find($inputDatas['id']);
        $pastData = $pastDataEloq;
        DB::beginTransaction();
        try {
            $imageObj = new ImageArrange();
            $pastDataEloq->delete();
            //往后的sort重新排序
            $this->model::where('sort', '>', $pastData->sort)->decrement('sort');
            DB::commit();
            $deleteStatus = $imageObj->deletePic(substr($pastData->pic_path, 1));
            //清除首页banner缓存
            $contll->deleteCache($pastDataEloq->flag);
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
