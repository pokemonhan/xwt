<?php
namespace App\Http\Controllers\BackendApi\Admin\Activity;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosAddRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosDeleteRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosDetailRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosEditRequest;
use App\Http\Requests\Backend\Admin\Activity\ActivityInfosSortRequest;
use App\Http\SingleActions\Backend\Admin\Activity\ActivityInfosAddAction;
use App\Http\SingleActions\Backend\Admin\Activity\ActivityInfosDeleteAction;
use App\Http\SingleActions\Backend\Admin\Activity\ActivityInfosDetailAction;
use App\Http\SingleActions\Backend\Admin\Activity\ActivityInfosEditAction;
use App\Http\SingleActions\Backend\Admin\Activity\ActivityInfosSortAction;
use Illuminate\Http\JsonResponse;

class ActivityInfosController extends BackEndApiMainController
{
    public $folderName = 'mobile_activity'; //活动图片存放的文件夹名称  (add,edit)
    public $redisKey = 'homepage_activity'; //缓存key (add,edit,delete,sort)

    /**
     * 活动列表
     * @param  ActivityInfosDetailRequest $request
     * @param  ActivityInfosDetailAction  $action
     * @return JsonResponse
     */
    public function detail(ActivityInfosDetailRequest $request, ActivityInfosDetailAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加活动
     * @param   ActivityInfosAddRequest $request
     * @param   ActivityInfosAddAction  $action
     * @return  JsonResponse
     */
    public function add(ActivityInfosAddRequest $request, ActivityInfosAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑活动
     * @param   ActivityInfosEditRequest $request
     * @param   ActivityInfosEditAction  $action
     * @return  JsonResponse
     */
    public function edit(ActivityInfosEditRequest $request, ActivityInfosEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除活动
     * @param   ActivityInfosDeleteRequest $request
     * @param   ActivityInfosDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(ActivityInfosDeleteRequest $request, ActivityInfosDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 活动排序
     * @param   ActivityInfosSortRequest $request
     * @param   ActivityInfosSortAction  $action
     * @return  JsonResponse
     */
    public function sort(ActivityInfosSortRequest $request, ActivityInfosSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
