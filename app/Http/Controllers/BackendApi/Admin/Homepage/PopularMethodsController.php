<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularMethodsSortRequest;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularMethodsAddAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularMethodsDeleteAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularMethodsDetailAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularMethodsEditAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularMethodsMethodsListAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularMethodsSortAction;
use Illuminate\Http\JsonResponse;

class PopularMethodsController extends BackEndApiMainController
{
    /**
     * 热门彩票二列表
     * @param   PopularMethodsDetailAction $action
     * @return  JsonResponse
     */
    public function detail(PopularMethodsDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门彩票二 添加热门彩票
     * @param   PopularMethodsAddRequest $request
     * @param   PopularMethodsAddAction  $action
     * @return  JsonResponse
     */
    public function add(PopularMethodsAddRequest $request, PopularMethodsAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 热门彩票二 编辑热门玩法
     * @param   PopularMethodsEditRequest $request
     * @param   PopularMethodsEditAction  $action
     * @return  JsonResponse
     */
    public function edit(PopularMethodsEditRequest $request, PopularMethodsEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除热门玩法
     * @param   PopularMethodsDeleteRequest $request
     * @param   PopularMethodsDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(PopularMethodsDeleteRequest $request, PopularMethodsDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 热门玩法拉动排序
     * @param   PopularMethodsSortRequest $request
     * @param   PopularMethodsSortAction  $action
     * @return  JsonResponse
     */
    public function sort(PopularMethodsSortRequest $request, PopularMethodsSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加热门玩法时选择的玩法列表
     * @param   PopularMethodsMethodsListAction  $action
     * @return  JsonResponse
     */
    public function methodsList(PopularMethodsMethodsListAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
