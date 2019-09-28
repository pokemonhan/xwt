<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\Domain;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainModAction
 * @package App\Http\SingleActions\Backend\Admin\Domain
 */
class DomainModDomainAction
{
    /**
     * @var Domain
     */
    protected $model;

    /**
     * @param  Domain $domain Domain.
     */
    public function __construct(Domain $domain)
    {
        $this->model = $domain;
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
