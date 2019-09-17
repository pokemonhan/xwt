<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\TaskScheduling\CronJob;
use Illuminate\Http\JsonResponse;

class TaskSchedulingEditAction
{
    protected $model;

    /**
     * @param  CronJob  $cronJob
     */
    public function __construct(CronJob $cronJob)
    {
        $this->model = $cronJob;
    }

    /**
     * 编辑任务调度
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $cronJobEloq = $this->model::find($inputDatas['id']);
        $contll->editAssignment($cronJobEloq, $inputDatas);
        $cronJobEloq->save();
        if ($cronJobEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '400', $cronJobEloq->errors()->messages());
        }
        CronJob::updateOPenCronJob(); //更新任务调度缓存
        return $contll->msgOut(true);
    }
}
