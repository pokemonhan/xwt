<?php

namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\BackendApi\Admin\Activity\ActivityInfosController;
use App\Lib\BaseCache;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\FrontendActivityContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ActivityInfosAddAction
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
     * 添加活动
     * @param  ActivityInfosController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(ActivityInfosController $contll, array $inputDatas): JsonResponse
    {
        //接收文件信息
        $imageObj = new ImageArrange();
        $depositPath = $imageObj->depositPath(
            $contll->folderName,
            $contll->currentPlatformEloq->platform_id,
            $contll->currentPlatformEloq->platform_name
        );
        $addDatas = $inputDatas;
        unset($addDatas['pic'], $addDatas['preview_pic']);
        //进行上传
        $previewPic = $imageObj->uploadImg($inputDatas['preview_pic'], $depositPath);
        if ($previewPic['success'] === false) {
            return $contll->msgOut(false, [], '400', $previewPic['msg']);
        }
        $addDatas['preview_pic_path'] = '/' . $previewPic['path'];
        if (isset($inputDatas['pic'])) {
            $image = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
            if ($image['success'] === false) {
                $imageObj->deletePic($previewPic['path']); //此次上传失败   删除前面上传的图片
                return $contll->msgOut(false, [], '400', $image['msg']);
            }
            $addDatas['pic_path'] = '/' . $image['path'];
        }
        DB::beginTransaction();
        //新添加的活动默认靠最前   sort=1 之前的活动sort自增1
        $this->model::where([
            ['sort', '>=', 1],
            ['type', $inputDatas['type']]
        ])->increment('sort');
        $addDatas['sort'] = 1;
        $addDatas['admin_id'] = $contll->partnerAdmin->id;
        $addDatas['admin_name'] = $contll->partnerAdmin->name;
        $addDatas['type'] = $inputDatas['type'];
        $activityEloq = new $this->model();
        $activityEloq->fill($addDatas);
        $activityEloq->save();
        if ($activityEloq->errors()->messages()) {
            DB::rollback();
            return $contll->msgOut(false, [], '400', $activityEloq->errors()->messages());
        }
        DB::commit();
        self::deleteTagsCache($contll->redisKey); //删除前台活动缓存
        return $contll->msgOut(true);
    }
}
