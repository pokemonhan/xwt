<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Exception;
use Illuminate\Http\JsonResponse;

class FrontendAllocatedModelEditAction
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
     * 编辑前端模块
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::find($inputDatas['id']);
        $pastDataEloq->label = $inputDatas['label'];
        $pastDataEloq->en_name = $inputDatas['en_name'];
        $pastDataEloq->save();
        if ($pastDataEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $pastDataEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
