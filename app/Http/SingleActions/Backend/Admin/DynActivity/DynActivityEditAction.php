<?php

namespace App\Http\SingleActions\Backend\Admin\DynActivity;

use App\Http\Controllers\BackendApi\Admin\DynActivity\DynActivityController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\DynActivity\BackendDynActivityList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DynActivityEditAction
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
            $pastDataEloq = $this->model::find($inputDatas['id']);
            $editDatas = $inputDatas;
            if (strtotime($inputDatas['start_time']) >= strtotime($inputDatas['end_time'])) {
                return $contll->msgOut(false, [], '400', '开始时间必须小与结束时间');
            }
            $pc_pic = $pastDataEloq->pc_pic;
            $wap_pic = $pastDataEloq->wap_pic;
            if (isset($inputDatas['pc_pic'])) {
                $previewPcPic = $this->savePic($imageObj,'pc',$contll,$inputDatas['pc_pic']);
                if ($previewPcPic['success'] === false) {
                    return $contll->msgOut(false, [], '400', $previewPcPic['msg']);
                }
                $editDatas['pc_pic'] = '/' . $previewPcPic['path'];
            } else {
                $editDatas['pc_pic'] = '';
            }
            if (isset($inputDatas['wap_pic'])) {
                $previewWapPic = $this->savePic($imageObj,'wap',$contll,$inputDatas['wap_pic']);
                if ($previewWapPic['success'] === false) {
                    return $contll->msgOut(false, [], '400', $previewWapPic['msg']);
                }
                $editDatas['wap_pic'] = '/' . $previewWapPic['path'];
            } else {
                $editDatas['wap_pic'] = '';
            }
            $editDatas['sort'] = $inputDatas['sort'] ?? 1;
            $editDatas['status'] = $inputDatas['status'] ?? $this->model::STATUS_DISABLE;
            $contll->editAssignment($pastDataEloq, $editDatas);
            $pastDataEloq->save();
            if ($pastDataEloq->errors()->messages()) {
                $this->deletePic($imageObj , $previewPcPic , $previewWapPic);
                return $contll->msgOut(false, [], '400', $pastDataEloq->errors()->messages());
            }
            $this->deletePicNoFinsh($imageObj, $pc_pic, $wap_pic);
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            $this->deletePic($imageObj , $previewPcPic , $previewWapPic);
            Log::channel('dy-activity')->info($e->getMessage());
            return $contll->msgOut(false,[],'400','系统异常');
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


    private function deletePicNoFinsh($imageObj, $pc_pic, $wap_pic)
    {
        if (isset($pc_pic) && !empty($pc_pic)) {
            $imageObj->deletePic(substr($pc_pic, 1));
        }
        if (isset($wap_pic) && !empty($wap_pic)) {
            $imageObj->deletePic(substr($wap_pic, 1));
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
