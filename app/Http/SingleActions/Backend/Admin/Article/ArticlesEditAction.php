<?php

namespace App\Http\SingleActions\Backend\Admin\Article;

use App\Http\Controllers\BackendApi\Admin\Article\ArticlesController;
use App\Models\Admin\Activity\BackendAdminMessageArticle;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

class ArticlesEditAction
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
     * 编辑文章
     * @param  ArticlesController  $contll
     * @param  array  $inputDatas
     * @return JsonResponse
     */
    public function execute(ArticlesController $contll, array $inputDatas): JsonResponse
    {
        try {
            $pastEloq = $this->model::find($inputDatas['id']);
            //插入 backend_admin_audit_flow_lists 审核表
            $auditFlowId = $contll->insertAuditFlow($inputDatas['apply_note']);
            $pastEloq->audit_flow_id = $auditFlowId;
            //
            $pastPicPath = $pastEloq->pic_path;
            $editDatas = $inputDatas;
            unset($editDatas['pic_name'], $editDatas['apply_note']);
            $contll->editAssignment($pastEloq, $editDatas);
            $pastEloq->status = 0;
            $pastEloq->last_update_admin_id = $contll->partnerAdmin->id;
            //查看是否修改图片
            $new_pic_path = $inputDatas['pic_path'];
            if ($new_pic_path != $pastPicPath) {
                //销毁缓存
                self::deleteCachePic($inputDatas['pic_name']);
                //删除原图
                $pastPicPathArr = explode('|', $pastPicPath);
                $contll->deleteImg($pastPicPathArr);
                //
                $pastEloq->pic_path = implode('|', $new_pic_path);
            }
            $pastEloq->save();
            //发送站内消息给管理员审核
            $contll->sendMessage();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
