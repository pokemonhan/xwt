<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use Illuminate\Http\JsonResponse;

class DomainAddAction
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
     * 添加域名
     * @param  BackEndApiMainController $contll
     * @param  mixed $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $haveFlag = $this->model->where('domain', $inputDatas['domain'])->get();
        if (count($haveFlag) > 0) {
            return $contll->msgOut(false, [], '102500');
        }
        try {
            $configure = new $this->model();
            $configure->fill($inputDatas);
            $configure->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
