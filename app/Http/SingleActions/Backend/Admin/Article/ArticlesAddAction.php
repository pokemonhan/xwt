<?php

namespace App\Http\SingleActions\Backend\Admin\Article;

use App\Http\Controllers\BackendApi\Admin\Article\ArticlesController;
use App\Models\Admin\Activity\BackendAdminMessageArticle;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Lib\BaseCache;

class ArticlesAddAction
{
    use BaseCache;

    protected $model;

    /**
     * @param  BackendAdminMessageArticle  $backendAdminMessageArticle
     */
    public function __construct(BackendAdminMessageArticle $backendAdminMessageArticle)
    {
        $this->model = $backendAdminMessageArticle;
    }

    /**
     * 发布文章
     * @param  ArticlesController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(ArticlesController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //插入 backend_admin_audit_flow_lists 审核表
            $auditFlowId = $contll->insertAuditFlow($inputDatas['apply_note']);
            //插入 BackendAdminMessageArticle 文章表
            $addDatas = $inputDatas;
            $addDatas['audit_flow_id'] = $auditFlowId;
            unset($addDatas['pic_name'], $addDatas['apply_note']);
            $maxSort = $this->model::select('sort')->max('sort');
            $sort = ++$maxSort;
            $addDatas['sort'] = $sort;
            $addDatas['status'] = 0;
            $addDatas['add_admin_id'] = $contll->partnerAdmin->id;
            $addDatas['last_update_admin_id'] = $contll->partnerAdmin->id;
            if (isset($inputDatas['pic_path']) && $inputDatas['pic_path'] !== '') {
                $addDatas['pic_path'] = implode('|', $inputDatas['pic_path']);
            }
            $articlesEloq = new $this->model();
            $articlesEloq->fill($addDatas);
            $articlesEloq->save();
            //文章发布成功  销毁图片缓存
            if (isset($inputDatas['pic_path'])) {
                self::deleteCachePic($inputDatas['pic_name']);
            }
            //发送站内消息给管理员审核
            $contll->sendMessage();
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
