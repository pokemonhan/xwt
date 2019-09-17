<?php

namespace App\Http\Controllers\BackendApi\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Domain\DomainListRequest;
use App\Http\Requests\Backend\Admin\Domain\DomainAddRequest;
use App\Http\Requests\Backend\Admin\Domain\DomainDelRequest;
use App\Http\Requests\Backend\Admin\Domain\DomainModRequest;
use App\Http\SingleActions\Backend\Admin\Domain\DomainListAction;
use App\Http\SingleActions\Backend\Admin\Domain\DomainAddAction;
use App\Http\SingleActions\Backend\Admin\Domain\DomainDelAction;
use App\Http\SingleActions\Backend\Admin\Domain\DomainModAction;

use Illuminate\Http\JsonResponse;

class DomainController extends BackEndApiMainController
{

    /**
     * 域名列表
     * @param  DomainListRequest $request
     * @param  DomainListAction $action
     * @return JsonResponse
     */
    public function list(DomainListRequest $request, DomainListAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return  $action->execute($this,$inputDatas);
    }

    /**
     * 添加域名
     * @param  DomainAddRequest $request
     * @param  DomainAddAction $action
     * @return JsonResponse
     */
    public function add(DomainAddRequest $request, DomainAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑域名
     * @param  DomainModRequest $request
     * @param  DomainModAction $action
     * @return JsonResponse
     */
    public function mod(DomainModRequest $request, DomainModAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 删除域名
     * @param  DomainDelRequest $request
     * @param  DomainDelAction $action
     * @return JsonResponse
     */
    public function del(DomainDelRequest $request, DomainDelAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
