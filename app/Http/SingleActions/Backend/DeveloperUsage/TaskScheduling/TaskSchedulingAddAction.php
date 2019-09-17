<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\TaskScheduling\CronJob;
use Illuminate\Http\JsonResponse;

class TaskSchedulingAddAction
{
    /**
     * 添加任务调度
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $data = CronJob::insertCronJob($inputDatas);
        if ($data['success'] === false) {
            return $contll->msgOut(false, [], '400', $data['message']);
        }
        CronJob::updateOPenCronJob(); //更新任务调度缓存
        return $contll->msgOut(true);
    }
}
