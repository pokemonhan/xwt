<?php

namespace App\Http\SingleActions\Backend\Admin\DynActivity;

use App\Http\Controllers\BackendApi\Admin\DynActivity\DynActivityController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\DynActivity\BackendDynActivityList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DynActivityAddAction
{
    protected $model;

    public function __construct(BackendDynActivityList $backendDynActivityList)
    {
        $this->model = $backendDynActivityList;
    }

    public function execute(DynActivityController $contll, array $inputDatas) :JsonResponse
    {
        $previewPcPic = []; //保存pc端图片的变量
        $previewWapPic = []; //保存wap端图片的变量
        $imageObj = new ImageArrange();
        try {
            $addDatas = $inputDatas;
            if (strtotime($addDatas['start_time']) >= strtotime($addDatas['end_time'])) {
                return $contll->msgOut(false, [], '400', '开始时间必须小与结束时间');
            }
            if (isset($inputDatas['pc_pic'])) {
                $previewPcPic = $this->savePic($imageObj,'pc',$contll,$inputDatas['pc_pic']);
                if ($previewPcPic['success'] === false) {
                    return $contll->msgOut(false, [], '400', $previewPcPic['msg']);
                }
                $addDatas['pc_pic'] = '/' . $previewPcPic['path'];
            }
            if (isset($inputDatas['wap_pic'])) {
                $previewWapPic = $this->savePic($imageObj,'wap',$contll,$inputDatas['wap_pic']);
                if ($previewWapPic['success'] === false) {
                    return $contll->msgOut(false, [], '400', $previewWapPic['msg']);
                }
                $addDatas['wap_pic'] = '/' . $previewWapPic['path'];
            }
            $addDatas['sort'] = $addDatas['sort'] ?? 1;
            $addDatas['status'] = $addDatas['status'] ?? $this->model::STATUS_DISABLE;
            $dynActivityEloq = new $this->model();
            $dynActivityEloq->fill($addDatas);
            $dynActivityEloq->save();
            if ($dynActivityEloq->errors()->messages()) {
                $this->deletePic($imageObj , $previewPcPic , $previewWapPic);
                return $contll->msgOut(false, [], '400', $dynActivityEloq->errors()->messages());
            }
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            $this->deletePic($imageObj , $previewPcPic , $previewWapPic);
            Log::channel('dy-activity')->info($e->getMessage());
            return $contll->msgOut(false,[],'400','系统错误');
        }
    }

    /**
     * 如果上传失败删除图片
     * @param mixed $imageObj
     * @param array $previewPcPic
     * @param array $previewWapPic
     */
    private function deletePic($imageObj, $previewPcPic, $previewWapPic)
    {
        if (isset($previewPcPic['path'])) {//上传失败   删除前面上传的图片
            $imageObj->deletePic($previewPcPic['path']);
        }
        if (isset($previewWapPic['path'])) {//上传失败   删除前面上传的图片
            $imageObj->deletePic($previewWapPic['path']);
        }
    }

    /**
     * 保存图片
     * @param mixed $imageObj
     * @param string $path
     * @param mixed $contll
     * @param mixed $picSource
     * @return mixed
     */
    private function savePic($imageObj, $path, $contll, $picSource)
    {
        $picSavePath = $imageObj->depositPath($contll->folderName.'/'.$path,$contll->currentPlatformEloq->platform_id, $contll->currentPlatformEloq->platform_name);
        $previewPic = $imageObj->uploadImg($picSource, $picSavePath);
        return $previewPic;
    }
}
