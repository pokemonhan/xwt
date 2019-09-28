<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainModAction
 * @package App\Http\SingleActions\Backend\Admin\Domain
 */
class DomainModDomainAction
{
    protected $model;

    /**
     * @param  BackendDomain $backendDomain
     */
    public function __construct(BackendDomain $backendDomain)
    {
        $this->model = $backendDomain;
    }

    /**
     * 修改域名
     * @param  BackEndApiMainController $contll     BackEndApiMainController.
     * @param  array                    $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $domainEloq = $this->model::find($inputDatas['id']);
        $contll->editAssignment($domainEloq, $inputDatas);
        try {
            $domainEloq->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}