<?php

namespace App\Http\SingleActions\Backend\Admin\Article;

use App\Http\Controllers\BackendApi\Admin\Article\ArticlesController;
use App\Models\Admin\Activity\BackendAdminMessageArticle;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ArticlesDeleteAction
{
    protected $model;

    /**
     * @param  BackendAdminMessageArticle  $backendAdminMessageArticle
     */
    public function __construct(BackendAdminMessageArticle $backendAdminMessageArticle)
    {
        $this->model = $backendAdminMessageArticle;
    }

    /**
     * 删除文章
     * @param  ArticlesController  $contll
     * @param  array  $inputDatas
     * @return JsonResponse
     */
    public function execute(ArticlesController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::find($inputDatas['id']);
        $sort = $pastDataEloq->sort;
        $picPathArr = explode('|', $pastDataEloq->pic_path);
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
            //排序
            $this->model::where('sort', '>', $sort)->decrement('sort');
            //删除图片
            $contll->deleteImg($picPathArr);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
