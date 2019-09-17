<?php

namespace App\Http\Controllers\BackendApi\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Notice\NoticeAddRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeDeleteRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeDetailRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeEditRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeSortRequest;
use App\Http\Requests\Backend\Admin\Notice\NoticeTopRequest;
use App\Http\SingleActions\Backend\Admin\Notice\NoticeAddAction;
use App\Http\SingleActions\Backend\Admin\Notice\NoticeDeleteAction;
use App\Http\SingleActions\Backend\Admin\Notice\NoticeDetailAction;
use App\Http\SingleActions\Backend\Admin\Notice\NoticeEditAction;
use App\Http\SingleActions\Backend\Admin\Notice\NoticeSortAction;
use App\Http\SingleActions\Backend\Admin\Notice\NoticeTopAction;
use Illuminate\Http\JsonResponse;

class NoticeController extends BackEndApiMainController
{
    /**
     * 公告|站内信 列表
     * @param  NoticeDetailAction $action
     * @return JsonResponse
     */
    public function detail(NoticeDetailRequest $request, NoticeDetailAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加 公告|站内信
     * @param  NoticeAddRequest $request
     * @param  NoticeAddAction  $action
     * @return JsonResponse
     */
    public function addNotice(NoticeAddRequest $request, NoticeAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑 公告|站内信
     * @param  NoticeEditRequest $request
     * @param  NoticeEditAction  $action
     * @return JsonResponse
     */
    public function edit(NoticeEditRequest $request, NoticeEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除 公告|站内信
     * @param  NoticeDeleteRequest $request
     * @param  NoticeDeleteAction  $action
     * @return JsonResponse
     */
    public function delete(NoticeDeleteRequest $request, NoticeDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 公告排序
     * @param  NoticeSortRequest $request
     * @param  NoticeSortAction  $action
     * @return JsonResponse
     */
    public function sort(NoticeSortRequest $request, NoticeSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
