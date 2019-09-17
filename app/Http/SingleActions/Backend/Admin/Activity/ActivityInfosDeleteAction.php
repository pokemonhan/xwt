<?php

namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\BackendApi\Admin\Activity\ActivityInfosController;
use App\Lib\BaseCache;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\FrontendActivityContent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ActivityInfosDeleteAction
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
     * 删除活动
     * @param  ActivityInfosController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(ActivityInfosController $contll, array $inputDatas): JsonResponse
    {
        $activityEloq = $this->model::find($inputDatas['id']);
        $type = $activityEloq->type;
        $sort = $activityEloq->sort;
        $picPath = $activityEloq->pic_path;
        $previewPicPath = $activityEloq->preview_pic_path;
        DB::beginTransaction();
        try {
            $activityEloq->delete();
            //排序
            $this->model::where([
                ['type', $type],
                ['sort', '>', $sort]
            ])
                ->decrement('sort');
            DB::commit();
            //删除图片
            $imageObj = new ImageArrange();
            $imageObj->deletePic(substr($picPath, 1));
            $imageObj->deletePic(substr($previewPicPath, 1));
            self::deleteTagsCache($contll->redisKey); //删除前台活动缓存
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
