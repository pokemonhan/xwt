<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\PopularLotteriesSortRequest;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularLotteriesAddAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularLotteriesDeleteAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularLotteriesDetailAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularLotteriesEditAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularLotteriesLotteriesListAction;
use App\Http\SingleActions\Backend\Admin\Homepage\PopularLotteriesSortAction;
use Illuminate\Http\JsonResponse;

class PopularLotteriesController extends BackEndApiMainController
{
    /**
     * 热门彩票列表
     * @param   PopularLotteriesDetailAction $action
     * @return  JsonResponse
     */
    public function detail(PopularLotteriesDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加热门彩票一
     * @param   PopularLotteriesAddRequest $request
     * @param   PopularLotteriesAddAction  $action
     * @return  JsonResponse
     */
    public function add(PopularLotteriesAddRequest $request, PopularLotteriesAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑热门彩票
     * @param   PopularLotteriesEditRequest $request
     * @param   PopularLotteriesEditAction  $action
     * @return  JsonResponse
     */
    public function edit(PopularLotteriesEditRequest $request, PopularLotteriesEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除热门彩票
     * @param   PopularLotteriesDeleteRequest $request
     * @param   PopularLotteriesDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(PopularLotteriesDeleteRequest $request, PopularLotteriesDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 热门彩票拉动排序
     * @param   PopularLotteriesSortRequest $request
     * @param   PopularLotteriesSortAction  $action
     * @return  JsonResponse
     */
    public function sort(PopularLotteriesSortRequest $request, PopularLotteriesSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 选择的彩种列表
     * @param   PopularLotteriesLotteriesListAction  $action
     * @return  JsonResponse
     */
    public function lotteriesList(PopularLotteriesLotteriesListAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
