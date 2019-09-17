<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Exception;
use Illuminate\Http\JsonResponse;

class HomepageUploadPicAction
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
     * 修改首页模块下的图片
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastData = $this->model::where('en_name', $inputDatas['en_name'])->first();
        $imageObj = new ImageArrange();
        $depositPath = $imageObj->depositPath(
            $inputDatas['en_name'],
            $contll->currentPlatformEloq->platform_id,
            $contll->currentPlatformEloq->platform_name
        );
        $picArr = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
        if ($picArr['success'] === false) {
            return $contll->msgOut(false, [], '400', $picArr['msg']);
        }
        $pastLogoPath = $pastData->value;
        try {
            $pastData->value = '/' . $picArr['path'];
            $pastData->save();
            //删除原图
            if ($pastLogoPath !== null) {
                $imageObj->deletePic(substr($pastLogoPath, 1));
            }
            FrontendAllocatedModel::getWebBasicContent(true); // 更新首页基本内容缓存
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $imageObj->deletePic($picArr['path']);
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
