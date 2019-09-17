<?php

namespace App\Http\Controllers\BackendApi\Admin\Article;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Article\ArticlesAddRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesDeleteRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesEditRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesSortRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesTopRequest;
use App\Http\Requests\Backend\Admin\Article\ArticlesUploadPicRequest;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesAddAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesDeleteAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesDetailAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesEditAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesInsertAuditFlowAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesSortAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesTopAction;
use App\Http\SingleActions\Backend\Admin\Article\ArticlesUploadPicAction;
use App\Lib\Common\AuditFlow;
use App\Lib\Common\ImageArrange;
use App\Lib\Common\InternalNoticeMessage;
use Illuminate\Http\JsonResponse;

class ArticlesController extends BackEndApiMainController
{
    /**
     * 文章列表
     * @param   ArticlesDetailAction $action
     * @return  JsonResponse
     */
    public function detail(ArticlesDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 发布文章
     * @param   ArticlesAddRequest $request
     * @param   ArticlesAddAction  $action
     * @return  JsonResponse
     */
    public function add(ArticlesAddRequest $request, ArticlesAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑文章
     * @param   ArticlesEditRequest $request
     * @param   ArticlesEditAction  $action
     * @return  JsonResponse
     */
    public function edit(ArticlesEditRequest $request, ArticlesEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除文章
     * @param   ArticlesDeleteRequest $request
     * @param   ArticlesDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(ArticlesDeleteRequest $request, ArticlesDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 文章排序
     * @param   ArticlesSortRequest $request
     * @param   ArticlesSortAction  $action
     * @return  JsonResponse
     */
    public function sort(ArticlesSortRequest $request, ArticlesSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 文章置顶
     * @param   ArticlesTopRequest $request
     * @param   ArticlesTopAction  $action
     * @return  JsonResponse
     */
    public function top(ArticlesTopRequest $request, ArticlesTopAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 图片上传
     * @param   ArticlesUploadPicRequest $request
     * @param   ArticlesUploadPicAction  $action
     * @return  JsonResponse
     */
    public function uploadPic(ArticlesUploadPicRequest $request, ArticlesUploadPicAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 插入审核表
     * @param   string $apply_note [备注]
     * @return  int
     */
    public function insertAuditFlow($apply_note): int
    {
        $auditFlow = new AuditFlow();
        return $auditFlow->insertAuditFlow($this->partnerAdmin->id, $this->partnerAdmin->name, $apply_note);
    }

    /**
     * 删除图片
     * @param  array $imgArr
     * @return void
     */
    public function deleteImg(array $imgArr): void
    {
        $imageObj = new ImageArrange();
        $imageObj->deleteImgs($imgArr);
    }

    /**
     * 发送站内消息给管理员审核
     * @return void
     */
    public function sendMessage(): void
    {
        $internalNoticeMessage = new InternalNoticeMessage();
        $internalNoticeMessage->adminAddArticles();
    }
}
