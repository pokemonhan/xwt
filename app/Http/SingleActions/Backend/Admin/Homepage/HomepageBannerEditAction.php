<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\Admin\Homepage\HomepageBannerController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Homepage\FrontendPageBanner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HomepageBannerEditAction
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
     * 编辑首页轮播图
     * @param  HomepageBannerController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(HomepageBannerController $contll, array $inputDatas): JsonResponse
    {
        $pastData = $this->model::find($inputDatas['id']);
        $editData = $inputDatas;
        unset($editData['pic']);
        //如果要修改图片  删除原图  上传新图
        if (isset($inputDatas['pic'])) {
            $imageObj = new ImageArrange();
            $imageObj->deletePic(substr($pastData['pic_path'], 1));
            $imageObj->deletePic(substr($pastData['thumbnail_path'], 1));
            $depositPath = $imageObj->depositPath(
                $contll->folderName,
                $contll->currentPlatformEloq->platform_id,
                $contll->currentPlatformEloq->platform_name
            );
            $picData = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
            if ($picData['success'] === false) {
                return $contll->msgOut(false, [], $picData['code']);
            }
            //上传缩略图
            $thumbnail = $imageObj->creatThumbnail($picData['path'], 100, 200);
            $editData['pic_path'] = '/' . $picData['path'];
            $editData['thumbnail_path'] = '/' . $thumbnail;
        }
        try {
            $contll->editAssignment($pastData, $editData);
            $pastData->save();
            //清除首页banner缓存
            $contll->deleteCache($pastData->flag);
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
