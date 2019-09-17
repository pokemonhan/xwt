<?php

namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\BackendApi\Admin\Activity\ActivityInfosController;
use App\Lib\BaseCache;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\FrontendActivityContent;
use Illuminate\Http\JsonResponse;

class ActivityInfosEditAction
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
     * 编辑活动
     * @param  ActivityInfosController   $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(ActivityInfosController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::find($inputDatas['id']);
        $editData = $inputDatas;
        //如果修改了图片 上传新图片
        $imageObj = new ImageArrange();
        if (isset($inputDatas['pic']) || isset($inputDatas['preview_pic'])) {
            $depositPath = $imageObj->depositPath(
                $contll->folderName,
                $contll->currentPlatformEloq->platform_id,
                $contll->currentPlatformEloq->platform_name
            );
            if (isset($inputDatas['pic'])) {
                unset($editData['pic']);
                $pastPic = $pastDataEloq->pic_path;
                $picdata = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
                if ($picdata['success'] === false) {
                    return $contll->msgOut(false, [], '', $picdata['msg']);
                }
                $pastDataEloq->pic_path = '/' . $picdata['path'];
            }
            if (isset($inputDatas['preview_pic'])) {
                unset($editData['preview_pic']);
                $pastPreviewPic = $pastDataEloq->preview_pic_path;
                $previewPic = $imageObj->uploadImg($inputDatas['preview_pic'], $depositPath);
                if ($previewPic['success'] === false) {
                    return $contll->msgOut(false, [], '', $previewPic['msg']);
                }
                $pastDataEloq->preview_pic_path = '/' . $previewPic['path'];
            }
        }
        $contll->editAssignment($pastDataEloq, $editData);
        $pastDataEloq->save();
        if ($pastDataEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $pastDataEloq->errors()->messages());
        }
        if (isset($inputDatas['pic'], $pastPic)) {
            $imageObj->deletePic(substr($pastPic, 1));
        }
        if (isset($inputDatas['preview_pic'], $pastPreviewPic)) {
            $imageObj->deletePic(substr($pastPreviewPic, 1));
        }
        self::deleteTagsCache($contll->redisKey); //删除前台活动缓存
        return $contll->msgOut(true);
    }
}
