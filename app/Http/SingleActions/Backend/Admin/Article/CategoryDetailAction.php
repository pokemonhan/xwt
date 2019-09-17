<?php

namespace App\Http\SingleActions\Backend\Admin\Article;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Activity\FrontendInfoCategorie;
use Illuminate\Http\JsonResponse;

class CategoryDetailAction
{
    protected $model;

    /**
     * @param  FrontendInfoCategorie  $frontendInfoCategorie
     */
    public function __construct(FrontendInfoCategorie $frontendInfoCategorie)
    {
        $this->model = $frontendInfoCategorie;
    }

    /**
     * 分类管理列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $categorieEloq = $this->model::with('parentCategorie:id,title')->get();
        $data = [];
        foreach ($categorieEloq as $key => $item) {
            $data[$key]['id'] = $item->id;
            $data[$key]['title'] = $item->title;
            $data[$key]['parent'] = $item->parent;
            $data[$key]['template'] = $item->template;
            $data[$key]['platform_id'] = $item->platform_id;
            $data[$key]['parent_title'] = $item->parentCategorie->title ?? null;
        }
        return $contll->msgOut(true, $data);
    }
}
