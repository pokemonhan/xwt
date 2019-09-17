<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Exception;
use Illuminate\Http\JsonResponse;

class MenuDeleteAction
{
    protected $model;

    /**
     * @param  BackendSystemMenu  $backendSystemMenu
     */
    public function __construct(BackendSystemMenu $backendSystemMenu)
    {
        $this->model = $backendSystemMenu;
    }

    /**
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $toDelete = $inputDatas['toDelete'];
        if (!empty($toDelete)) {
            try {
                $datas = $this->model->find($toDelete)->each(static function ($product) {
                    $data[] = $product->toArray();
                    $product->delete();
                    return $data;
                });
                $this->model->refreshStar();
                return $contll->msgOut(true, $datas);
            } catch (Exception $e) {
                return $contll->msgOut(false, [], '0002', $e->getMessage());
            }
        } else {
            return $contll->msgOut(false);
        }
    }
}
