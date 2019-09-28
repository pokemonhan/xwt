<?php

namespace App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Http\JsonResponse;

/**
 * Class MenuEditAction
 * @package App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu
 */
class MenuEditAction
{
    /**
     * @var BackendSystemMenu 模型.
     */
    protected $model;

    /**
     * @param  BackendSystemMenu $backendSystemMenu 菜单.
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
        $menuEloq = $this->model::find($inputDatas['menuId']);
        $menuEloq->label = $inputDatas['label'];
        $menuEloq->en_name = $inputDatas['en_name'];
        $menuEloq->display = $inputDatas['display'];
        $menuEloq->icon = $inputDatas['icon'] ?? null;
        $menuEloq->type = $inputDatas['type'];
        if ($parent === true) {
            $menuEloq->route = '#';
            $menuEloq->pid = 0;
        } else {
            $menuEloq->route = $inputDatas['route'];
            $menuEloq->pid = $inputDatas['parentId'];
        }
        $data = $menuEloq->toArray();
        if ($menuEloq->save()) {
            $menuEloq->refreshStar();
            return $contll->msgOut(true, $data);
        } else {
            return $contll->msgOut(false, [], '100801');
        }
    }
}
