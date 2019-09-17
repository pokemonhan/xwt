<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HomepageUploadIcoAction
{
    protected $model;

    /**
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 上传前台网站头ico
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastData = $this->model::where('en_name', 'frontend.ico')->first();
        $imageObj = new ImageArrange();
        $folderName = 'frontend';
        $depositPath = $imageObj->depositPath(
            $folderName,
            $contll->currentPlatformEloq->platform_id,
            $contll->currentPlatformEloq->platform_name
        ) . '/ico';
        $icoArr = $imageObj->uploadImg($inputDatas['ico'], $depositPath);
        $pastIco = $pastData->value;
        try {
            $pastData->value = '/' . $icoArr['path'];
            $pastData->save();
            //删除原图
            if ($pastIco !== null) {
                $imageObj->deletePic(substr($pastIco, 1));
            }
            FrontendAllocatedModel::getWebBasicContent(true); // 更新首页基本内容缓存
            return $contll->msgOut(true);
        } catch (Exception $e) {
            //删除上传成功的图片
            $imageObj->deletePic($icoArr['path']);
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
