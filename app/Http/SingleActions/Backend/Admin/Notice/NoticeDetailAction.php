<?php

namespace App\Http\SingleActions\Backend\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Notice\FrontendMessageNoticesContent;
use Illuminate\Http\JsonResponse;

class NoticeDetailAction
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
     * 公告列表
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $searchAbleFields = ['type'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        if ((int) $inputDatas['type'] === FrontendMessageNoticesContent::TYPE_NOTICE) {
            $orderFields = 'sort';
            $orderFlow = 'asc';
        }
        $datas = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
        return $contll->msgOut(true, $datas);
    }
}
