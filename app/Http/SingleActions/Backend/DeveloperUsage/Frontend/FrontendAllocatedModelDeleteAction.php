<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use App\Models\DeveloperUsage\Frontend\FrontendAppRoute;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FrontendAllocatedModelDeleteAction
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
     * 删除前端模块
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $moduleEloq = $this->model::find($inputDatas['id']);
        //检查是否存在下级
        $deleteIds[] = $inputDatas['id'];
        $childs = $moduleEloq->childs->pluck('id')->toArray();
        if ($childs !== null) {
            $deleteIds = array_merge($deleteIds, $childs);
            $grandson = $this->model::whereIn('pid', $childs)->pluck('id')->toArray();
            if ($grandson !== null) {
                $deleteIds = array_merge($deleteIds, $grandson);
            }
        }
        DB::beginTransaction();
        try {
            $this->model::whereIn('id', $deleteIds)->delete();
            //删除绑定该模块的路由
            $issetWebRoute = FrontendWebRoute::whereIn('frontend_model_id', $deleteIds)->exists();
            if ($issetWebRoute === true) {
                FrontendWebRoute::whereIn('frontend_model_id', $deleteIds)->delete();
            }
            $issetAppRoute = FrontendAppRoute::whereIn('frontend_model_id', $deleteIds)->exists();
            if ($issetAppRoute === true) {
                FrontendAppRoute::whereIn('frontend_model_id', $deleteIds)->delete();
            }
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
