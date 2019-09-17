<?php

namespace App\Http\SingleActions\Backend\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Notice\FrontendMessageNoticesContent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class NoticeDeleteAction
{
    protected $model;

    /**
     * @param  FrontendMessageNoticesContent  $frontendMessageNoticesContent
     */
    public function __construct(FrontendMessageNoticesContent $frontendMessageNoticesContent)
    {
        $this->model = $frontendMessageNoticesContent;
    }

    /**
     * 删除 公告|站内信
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            $noticesContentEloq = $this->model::find($inputDatas['id']);
            $picStr = $noticesContentEloq->pic_path;

            //删除公告时，处理sort
            if ($noticesContentEloq->type === $this->model::TYPE_NOTICE && is_int($noticesContentEloq->sort)) {
                $this->model::where('sort', '>', $noticesContentEloq->sort)->decrement('sort');
            }

            $noticesContentEloq->delete();
            DB::commit();
            if ($picStr !== null) {
                $picArr = explode('|', $picStr);
                $imageObj = new ImageArrange();
                $imageObj->deleteImgs($picArr);
            }
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
