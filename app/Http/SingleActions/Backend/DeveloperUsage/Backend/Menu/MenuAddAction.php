<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Http\JsonResponse;

/**
 * Class MenuAddAction
 * @package App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu
 */
class MenuAddAction
{
    /**
     * @var BackendSystemMenu 模型.
     */
    protected $model;

    /**
     * @param  BackendSystemMenu $backendSystemMenu 菜单模型.
     */
    public function __construct(BackendSystemMenu $backendSystemMenu)
    {
        $this->model = $backendSystemMenu;
    }

    /**
     * @param  BackEndApiMainController $contll     控制器.
     * @param  array                    $inputDatas 数据.
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
        $menuEloq->type = $inputDatas['type'];
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
