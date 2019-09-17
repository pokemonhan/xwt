<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Http\JsonResponse;

class MenuChangeParentAction
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
        $parseDatas = json_decode($inputDatas['dragResult'], true);
        $itemProcess = [];
        if (!empty($parseDatas)) {
            $itemProcess = $this->model->changeParent($parseDatas);
            return $contll->msgOut(true, $itemProcess);
        } else {
            return $contll->msgOut(false);
        }
    }
}
