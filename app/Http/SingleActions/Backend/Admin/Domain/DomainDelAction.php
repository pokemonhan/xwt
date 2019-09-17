<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use Illuminate\Http\JsonResponse;

class DomainDelAction
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
     * 删除域名
     * @param  BackEndApiMainController $contll
     * @param  mixed $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        try {
            $this->model::where('id', $inputDatas['id'])->delete();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
