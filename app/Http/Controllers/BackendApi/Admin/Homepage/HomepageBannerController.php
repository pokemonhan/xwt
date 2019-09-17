<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerSortRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerDetailRequest;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageActivityListAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerAddAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerDeleteAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerDetailAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerEditAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerPicStandardAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageBannerSortAction;
use App\Http\SingleActions\Backend\Admin\Homepage\HomepageReplaceImageAction;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;

class HomepageBannerController extends BackEndApiMainController
{
    use BaseCache;

    public $folderName = 'homepage_banner';

    /**
     * 首页轮播图列表
     * @param    HomepageBannerDetailAction $action
     * @return   JsonResponse
     */
    public function detail(HomepageBannerDetailRequest $request, HomepageBannerDetailAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加首页轮播图
     * @param   HomepageBannerAddRequest $request
     * @param   HomepageBannerAddAction $action
     * @return  JsonResponse
     */
    public function add(HomepageBannerAddRequest $request, HomepageBannerAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑首页轮播图
     * @param   HomepageBannerEditRequest $request
     * @param   HomepageBannerEditAction $action
     * @return  JsonResponse
     */
    public function edit(HomepageBannerEditRequest $request, HomepageBannerEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除首页轮播图
     * @param   HomepageBannerDeleteRequest $request
     * @param   HomepageBannerDeleteAction $action
     * @return  JsonResponse
     */
    public function delete(HomepageBannerDeleteRequest $request, HomepageBannerDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 首页轮播图排序
     * @param   HomepageBannerSortRequest $request
     * @param   HomepageBannerSortAction $action
     * @return  JsonResponse
     */
    public function sort(HomepageBannerSortRequest $request, HomepageBannerSortAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 操作轮播图时获取的活动列表
     * @param   HomepageActivityListAction $action
     * @return  JsonResponse
     */
    public function activityList(HomepageActivityListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 上传图片的规格
     * @param   HomepageBannerPicStandardAction $action
     * @return  JsonResponse
     */
    public function picStandard(HomepageBannerPicStandardAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 清除首页banner缓存
     * @param  int|bool $flag  指定清除缓存或者同时清除 默认值为false
     * @return void
     */
    public function deleteCache($flag = false): void
    {
        $cacheName = $flag == 1 ? 'homepage_banner_web' : 'homepage_banner_app';
        if ($flag == 1 || $flag == 2) {
            self::deleteTagsCache($cacheName);
        } else {
            //同时清除
            self::deleteTagsCache('homepage_banner_web');
            self::deleteTagsCache('homepage_banner_app');
        }
    }
}
