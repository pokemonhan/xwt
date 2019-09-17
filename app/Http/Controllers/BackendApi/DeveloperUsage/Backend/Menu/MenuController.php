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

class MenuController extends BackEndApiMainController
{

    public function getAllMenu()
    {
        return $this->msgOut(true, $this->fullMenuLists);
    }

    public function currentPartnerMenu()
    {
        return $this->msgOut(true, $this->partnerMenulists);
    }

    /**
     *
     * @param  MenuAllRequireInfosRequest $request
     * @param  MenuAllRequireInfosAction  $action
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
     * @param   MenuAddRequest $request
     * @param   MenuAddAction  $action
     * @return  JsonResponse
     */
    public function add(MenuAddRequest $request, MenuAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param   MenuDeleteRequest $request
     * @param   MenuDeleteAction  $action
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
     * @param   MenuEditRequest $request
     * @param   MenuEditAction  $action
     * @return  JsonResponse
     */
    public function edit(MenuEditRequest $request, MenuEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param   MenuChangeParentAction  $action
     * @return  JsonResponse
     */
    public function changeParent(MenuChangeParentAction $action): JsonResponse
    {
        return $action->execute($this, $this->inputs);
    }
}
