<?php

namespace App\Http\Controllers\BackendApi\Admin\Article;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\SingleActions\Backend\Admin\Article\CategoryDetailAction;
use App\Http\SingleActions\Backend\Admin\Article\CategorySelectAction;
use Illuminate\Http\JsonResponse;

class CategoryController extends BackEndApiMainController
{

    /**
     * 分类管理列表
     * @param   CategoryDetailAction $action
     * @return  JsonResponse
     */
    public function detail(CategoryDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 操作文章时获取的分类列表
     * @return JsonResponse
     */
    public function select(CategorySelectAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
