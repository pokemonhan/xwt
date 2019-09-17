<?php

namespace App\Http\SingleActions\Backend\Admin\Activity;

use App\Http\Controllers\BackendApi\Admin\Activity\ActivityInfosController;
use App\Models\Admin\Activity\FrontendActivityContent;
use Illuminate\Http\JsonResponse;

class ActivityInfosDetailAction
{
    protected $model;

    /**
     * @param  FrontendActivityContent  $frontendActivityContent
     */
    public function __construct(FrontendActivityContent $frontendActivityContent)
    {
        $this->model = $frontendActivityContent;
    }

    /**
     * 活动列表
     * @param  ActivityInfosController  $contll
     * @param  array                    $inputDatas
     * @return JsonResponse
     */
    public function execute(ActivityInfosController $contll, array $inputDatas): JsonResponse
    {
        $contll->inputs['type'] = $inputDatas['type'];
        $searchAbleFields = ['title', 'type', 'status', 'admin_name', 'is_time_interval'];
        $orderFields = 'sort';
        $orderFlow = 'asc';
        $datas = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
        return $contll->msgOut(true, $datas);
    }
}
