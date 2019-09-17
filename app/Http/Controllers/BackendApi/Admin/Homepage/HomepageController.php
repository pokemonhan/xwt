<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\HomepageEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageUploadIcoRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageUploadPicRequest;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageEditAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageNavOneAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepagePageModelAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageUploadIcoAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageUploadPicAction;
use Illuminate\Http\JsonResponse;

class HomepageController extends BackEndApiMainController
{

    /**
     * 导航一列表
     * @param   HomepageNavOneAction $action
     * @return  JsonResponse
     */
    public function navOne(HomepageNavOneAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 主题板块列表
     * @param   HomepagePageModelAction $action
     * @return  JsonResponse
     */
    public function pageModel(HomepagePageModelAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 编辑首页模块
     * @param   HomepageEditRequest $request
     * @param   HomepageEditAction  $action
     * @return  JsonResponse
     */
    public function edit(HomepageEditRequest $request, HomepageEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 修改首页模块下的图片
     * @param   HomepageUploadPicRequest $request
     * @param   HomepageUploadPicAction  $action
     * @return  JsonResponse
     */
    public function uploadPic(HomepageUploadPicRequest $request, HomepageUploadPicAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 上传前台网站头ico
     * @param   HomepageUploadIcoRequest $request
     * @param   HomepageUploadIcoAction  $action
     * @return  JsonResponse
     */
    public function uploadIco(HomepageUploadIcoRequest $request, HomepageUploadIcoAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
