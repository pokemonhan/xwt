<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\LotteryNoticeAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\LotteryNoticeDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\LotteryNoticeEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\LotteryNoticeSortRequest;
use App\Http\SingleActions\Backend\Admin\Homepage\LotteryNoticeAddAction;
use App\Http\SingleActions\Backend\Admin\Homepage\LotteryNoticeDeleteAction;
use App\Http\SingleActions\Backend\Admin\Homepage\LotteryNoticeDetailAction;
use App\Http\SingleActions\Backend\Admin\Homepage\LotteryNoticeEditAction;
use App\Http\SingleActions\Backend\Admin\Homepage\LotteryNoticeSortAction;
use Illuminate\Http\JsonResponse;

class LotteryNoticeController extends BackEndApiMainController
{
    /**
     * 开奖公告的彩种列表
     * @param   LotteryNoticeDetailAction $action
     * @return  JsonResponse
     */
    public function detail(LotteryNoticeDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加开奖公告的彩种
     * @param   LotteryNoticeAddRequest $request
     * @param   LotteryNoticeAddAction  $action
     * @return  JsonResponse
     */
    public function add(LotteryNoticeAddRequest $request, LotteryNoticeAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑开奖公告的彩种
     * @param   LotteryNoticeEditRequest $request
     * @param   LotteryNoticeEditAction  $action
     * @return  JsonResponse
     */
    public function edit(LotteryNoticeEditRequest $request, LotteryNoticeEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除开奖公告的彩种
     * @param   LotteryNoticeDeleteRequest $request
     * @param   LotteryNoticeDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(LotteryNoticeDeleteRequest $request, LotteryNoticeDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 排序开奖公告的彩种
     * @param   LotteryNoticeSortRequest $request
     * @param   LotteryNoticeSortAction  $action
     * @return  JsonResponse
     */
    public function sort(LotteryNoticeSortRequest $request, LotteryNoticeSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
