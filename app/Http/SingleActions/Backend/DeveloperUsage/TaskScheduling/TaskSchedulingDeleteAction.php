<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\TaskScheduling\CronJob;
use Illuminate\Http\JsonResponse;

class TaskSchedulingDeleteAction
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
     * 删除任务调度
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $cronJobEloq = $this->model::find($inputDatas['id']);
        $cronJobEloq->delete();
        if ($cronJobEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $cronJobEloq->errors()->messages());
        }
        CronJob::updateOPenCronJob(); //更新任务调度缓存
        return $contll->msgOut(true);
    }
}
