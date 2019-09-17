<?php
/**
 * @Author: Fish
 * @Date:   2019/7/4 15:45
 */

namespace App\Http\Controllers\BackendApi\Admin\Help;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Help\HelpCenterAddRequest;
use App\Http\Requests\Backend\Admin\Help\HelpCenterDeleteRequest;
use App\Http\Requests\Backend\Admin\Help\HelpCenterEditRequest;
use App\Http\Requests\Backend\Admin\Help\HelpCenterUploadPicRequest;
use App\Http\SingleActions\Backend\Admin\Help\HelpCenterAddAction;
use App\Http\SingleActions\Backend\Admin\Help\HelpCenterDeleteAction;
use App\Http\SingleActions\Backend\Admin\Help\HelpCenterDetailAction;
use App\Http\SingleActions\Backend\Admin\Help\HelpCenterEditAction;
use Illuminate\Http\JsonResponse;

class HelpCenterController extends BackEndApiMainController
{
    /**
     * 帮助中心列表
     * @param  HelpCenterDetailAction $action
     * @return JsonResponse
     */
    public function detail(HelpCenterDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加
     * @param  HelpCenterAddRequest $request
     * @param  HelpCenterAddAction  $action
     * @return JsonResponse
     */
    public function add(HelpCenterAddRequest $request, HelpCenterAddAction $action): JsonResponse
    {
        $input = $request->validated();
        return $action->execute($this, $input);
    }

    /**
     * 编辑
     * @param  HelpCenterEditRequest $request
     * @param  HelpCenterEditAction  $action
     * @return JsonResponse
     */
    public function edit(HelpCenterEditRequest $request, HelpCenterEditAction $action): JsonResponse
    {
        $input = $request->validated();
        return $action->execute($this, $input);
    }

    /**
     * 删除
     * @param  HelpCenterDeleteRequest $request
     * @param  HelpCenterDeleteAction  $action
     * @return JsonResponse
     */
    public function delete(HelpCenterDeleteRequest $request, HelpCenterDeleteAction $action): JsonResponse
    {
        $input = $request->validated();
        return $action->execute($this, $input);
    }
}
