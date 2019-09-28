<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuAllRequireInfosRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuEditRequest;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu\MenuAddAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu\MenuAllRequireInfosAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu\MenuChangeParentAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu\MenuDeleteAction;
use App\Http\SingleActions\Backend\DeveloperUsage\Backend\Menu\MenuEditAction;
use Illuminate\Http\JsonResponse;

/**
 * Class MenuController
 * @package App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Menu
 */
class MenuController extends BackEndApiMainController
{
    /**
     * @return JsonResponse
     */
    public function getAllMenu()
    {
        return $this->msgOut(true, $this->fullMenuLists);
    }

    /**
     * @return JsonResponse
     */
    public function currentPartnerMenu()
    {
        return $this->msgOut(true, $this->partnerMenulists);
    }

    /**
     *
     * @param  MenuAllRequireInfosRequest $request 验证器.
     * @param  MenuAllRequireInfosAction  $action  处理器.
     * @return JsonResponse
     */
    public function allRequireInfos(
        MenuAllRequireInfosRequest $request,
        MenuAllRequireInfosAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param   MenuAddRequest $request 验证器.
     * @param   MenuAddAction  $action  处理器.
     * @return  JsonResponse
     */
    public function doadd(MenuAddRequest $request, MenuAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param   MenuDeleteRequest $request 验证器.
     * @param   MenuDeleteAction  $action  处理器.
     * @return  JsonResponse
     */
    public function delete(MenuDeleteRequest $request, MenuDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     *  菜单编辑接口
     * (?!\.) - don't allow . at start
     * (?!.*?\.\.) - don't allow 2 consecutive dots
     * (?!.*\.$) - don't allow . at end
     * @param   MenuEditRequest $request 验证器.
     * @param   MenuEditAction  $action  处理器.
     * @return  JsonResponse
     */
    public function edit(MenuEditRequest $request, MenuEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param MenuChangeParentAction $action 处理器.
     * @return  JsonResponse
     */
    public function changeParent(MenuChangeParentAction $action): JsonResponse
    {
        return $action->execute($this, $this->inputs);
    }
}
