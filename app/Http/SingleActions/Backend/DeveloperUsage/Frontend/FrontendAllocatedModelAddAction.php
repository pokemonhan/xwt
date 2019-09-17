<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;

class FrontendAllocatedModelAddAction
{
    protected $model;

    /**
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 添加前端模块
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        if ($inputDatas['pid'] != 0) {
            $checkParentLevel = $this->model::where('id', $inputDatas['pid'])->first();
            if ($checkParentLevel->level === 3) {
                return $contll->msgOut(false, [], '101603');
            }
        }
        $moduleEloq = new $this->model;
        $moduleEloq->fill($inputDatas);
        $moduleEloq->save();
        if ($moduleEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $moduleEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
