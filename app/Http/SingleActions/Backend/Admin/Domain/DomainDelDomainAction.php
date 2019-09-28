<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainDelAction
 * @package App\Http\SingleActions\Backend\Admin\Domain
 */
class DomainDelDomainAction
{
    /**
     * @var BackendDomain
     */
    protected $model;

    /**
     * @param  BackendDomain $backendDomain BackendDomain.
     */
    public function __construct(BackendDomain $backendDomain)
    {
        $this->model = $backendDomain;
    }

    /**
     * 删除域名
     * @param  BackEndApiMainController $contll     BackEndApiMainController.
     * @param  array                    $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        try {
            $this->model::where('id', $inputDatas['id'])->delete();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
