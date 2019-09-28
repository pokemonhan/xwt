<?php

namespace App\Http\Controllers\BackendApi\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Domain\DomainAddDomainRequest;
use App\Http\Requests\Backend\Admin\Domain\DomainDelDomainRequest;
use App\Http\Requests\Backend\Admin\Domain\DomainModDomainRequest;
use App\Http\SingleActions\Backend\Admin\Domain\DomainListAction;
use App\Http\SingleActions\Backend\Admin\Domain\DomainAddDomainAction;
use App\Http\SingleActions\Backend\Admin\Domain\DomainDelDomainAction;
use App\Http\SingleActions\Backend\Admin\Domain\DomainModDomainAction;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainController
 * @package App\Http\Controllers\BackendApi\Admin\Domain
 */
class DomainController extends BackEndApiMainController
{
    /**
     * 域名列表
     * @param DomainListAction $action 逻辑处理.
     * @return JsonResponse
     */
    public function list(DomainListAction $action): JsonResponse
    {
        return  $action->execute($this);
    }

    /**
     * 添加域名
     * @param  DomainAddDomainRequest $request 请求.
     * @param  DomainAddDomainAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function addDomain(DomainAddDomainRequest $request, DomainAddDomainAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑域名
     * @param  DomainModDomainRequest $request 请求.
     * @param  DomainModDomainAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function modDomain(DomainModDomainRequest $request, DomainModDomainAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 删除域名
     * @param  DomainDelDomainRequest $request 请求.
     * @param  DomainDelDomainAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function delDomain(DomainDelDomainRequest $request, DomainDelDomainAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
