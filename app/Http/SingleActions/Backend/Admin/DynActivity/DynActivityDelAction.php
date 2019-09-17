<?php

namespace App\Http\SingleActions\Backend\Admin\DynActivity;

use App\Http\Controllers\BackendApi\Admin\DynActivity\DynActivityController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\DynActivity\BackendDynActivityList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DynActivityDelAction
{
    protected $model;

    public function __construct(BackendDynActivityList $backendDynActivityList)
    {
        $this->model = $backendDynActivityList;
    }

    public function execute(DynActivityController $contll, array $inputDatas) :JsonResponse
    {
        try {
            $pastDataEloq = $this->model::find($inputDatas['id']);
            if ($this->model::where('id', $inputDatas['id'])->delete()) {
                $imageObj = new ImageArrange();
                if (isset($pastDataEloq->pc_pic) && !empty($pastDataEloq->pc_pic)) {
                    $imageObj->deletePic(substr($pastDataEloq->pc_pic, 1));
                }
                if (isset($pastDataEloq->wap_pic) && !empty($pastDataEloq->wap_pic)) {
                    $imageObj->deletePic(substr($pastDataEloq->wap_pic, 1));
                }
                return $contll->msgOut(true);
            }
            return $contll->msgOut(false,[],'400','操作失败');
        } catch (\Exception $e) {
            if (!env('APP_DEBUG')) {
                Log::info($e->getMessage());
                return $contll->msgOut(false,[],'400','系统异常');
            }
            return $contll->msgOut(false,[],'400',$e->getMessage());
        }
    }
}
