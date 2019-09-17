<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\TaskScheduling\CronJob;
use Illuminate\Http\JsonResponse;

class TaskSchedulingDetailAction
{
    /**
     * 任务调度列表
     * @param   BackEndApiMainController  $contll
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $data = CronJob::getAllCronJob();
        return $contll->msgOut(true, $data);
    }
}
