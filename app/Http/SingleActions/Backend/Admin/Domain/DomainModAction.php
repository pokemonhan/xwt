<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use Illuminate\Http\JsonResponse;

class DomainModAction
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
     * @param  BackEndApiMainController $contll
     * @param  mixed $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        if (count($inputDatas) < 2) {
            return $contll->msgOut(false, [], '102501');
        }

        $pastEloq = $this->model::find($inputDatas['id']);
        $contll->editAssignment($pastEloq, $inputDatas);
        try {
            $pastEloq->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
