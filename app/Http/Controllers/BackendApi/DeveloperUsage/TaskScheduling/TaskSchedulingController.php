<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\TaskScheduling;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingEditRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingDetailAction;
use App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingEditAction;
use App\Http\SingleActions\Backend\DeveloperUsage\TaskScheduling\TaskSchedulingDeleteAction;
use Illuminate\Http\JsonResponse;

class TaskSchedulingController extends BackEndApiMainController
{
    /**
     * 任务调度列表
     * @param  TaskSchedulingDetailAction $action
     * @return JsonResponse
     */
    public function detail(TaskSchedulingDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加任务调度
     * @param TaskSchedulingAddRequest $request
     * @param TaskSchedulingAddAction  $action
     * @return JsonResponse
     */
    public function add(TaskSchedulingAddRequest $request, TaskSchedulingAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑任务调度
     * @param  TaskSchedulingEditRequest $request
     * @param  TaskSchedulingEditAction  $action
     * @return JsonResponse
     */
    public function edit(TaskSchedulingEditRequest $request, TaskSchedulingEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除任务调度
     * @param  TaskSchedulingDeleteRequest $request
     * @param  TaskSchedulingDeleteAction  $action
     * @return JsonResponse
     */
    public function delete(TaskSchedulingDeleteRequest $request, TaskSchedulingDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
