<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Http\JsonResponse;

class MenuAddAction
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
        $parent = false;
        if (isset($inputDatas['isParent']) && $inputDatas['isParent'] === '1') {
            $parent = true;
        }
        $MenuEloq = $this->model::where('label', $inputDatas['label'])->first();
        if ($MenuEloq !== null) {
            return $contll->msgOut(false, [], '100800');
        }
        $menuEloq = new BackendSystemMenu();
        $menuEloq->label = $inputDatas['label'];
        $menuEloq->en_name = $inputDatas['en_name'];
        $menuEloq->route = $inputDatas['route'];
        $menuEloq->display = $inputDatas['display'];
        $menuEloq->icon = $inputDatas['icon'] ?? null;
        $menuEloq->sort = $inputDatas['sort'];
        if ($parent === false) {
            $menuEloq->pid = $inputDatas['parentId'];
            $menuEloq->level = $inputDatas['level'];
        }
        $menuEloq->save();
        if ($menuEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $menuEloq->errors()->messages());
        }
        $menuEloq->refreshStar();
        return $contll->msgOut(true, $menuEloq->toArray());
    }
}
