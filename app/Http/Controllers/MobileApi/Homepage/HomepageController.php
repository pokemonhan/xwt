<?php

namespace App\Http\Controllers\MobileApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Homepage\HomepageReadMessageRequest;
use App\Http\Requests\Frontend\Homepage\HomePageNoticeRequest;
use App\Http\SingleActions\Frontend\Homepage\HomepageActivityListAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageGetBasicContentAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageGetWebInfoAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageNoticeAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageRankingAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageReadMessageAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageShowHomepageModelAction;
use App\Http\SingleActions\Frontend\Homepage\HompageActivityAction;
use App\Http\SingleActions\Frontend\Homepage\HompageBannerAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularMethodsAction;
use App\Http\SingleActions\Mobile\Homepage\HompagePopularLotteriesAction;
use Illuminate\Http\JsonResponse;

class HomepageController extends FrontendApiMainController
{
    private $bannerFlag = 2; //网页端banner
    public $tags = 'homepage';

    /**
     * 需要展示的前台模块
     * @param  HomepageShowHomepageModelAction $action
     * @return JsonResponse
     */
    public function showHomepageModel(HomepageShowHomepageModelAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页轮播图列表
     * @param  HompageBannerAction  $action
     * @return JsonResponse
     */
    public function banner(HompageBannerAction $action): JsonResponse
    {
        return $action->execute($this, $this->bannerFlag);
    }

    /**
     * 热门彩票一
     * @param  HompagePopularLotteriesAction $action
     * @return JsonResponse
     */
    public function popularLotteries(HompagePopularLotteriesAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门彩票二-玩法
     * @param  HompagePopularMethodsAction $action
     * @return JsonResponse
     */
    public function popularMethods(HompagePopularMethodsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门活动
     * @param  HompageActivityAction $action
     * @return JsonResponse
     */
    public function activity(HompageActivityAction $action): JsonResponse
    {
        return $action->execute($this, 2);
    }

    /**
     * 首页活动列表
     * @param  HomepageActivityListAction $action
     * @return JsonResponse
     */
    public function activityList(HomepageActivityListAction $action): JsonResponse
    {
        $inputDatas['type'] = '2';
        return $action->execute($this, $inputDatas);
    }

    /**
     * 公告|站内信 列表
     * @param  HomepageNoticeAction $action
     * @return JsonResponse
     */
    public function notice(HomePageNoticeRequest $request, HomepageNoticeAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 公告|站内信 已读处理
     * @param  HomepageReadMessageRequest $request
     * @param  HomepageReadMessageAction  $action
     * @return JsonResponse
     */
    public function readMessage(HomepageReadMessageRequest $request, HomepageReadMessageAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 首页中奖排行榜
     * @param  HomepageRankingAction $action
     * @return JsonResponse
     */
    public function ranking(HomepageRankingAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取网站基本信息
     * @param  HomepageGetWebInfoAction $action
     * @return JsonResponse
     */
    public function getWebInfo(HomepageGetWebInfoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取首页基本内容
     * @param  HomepageGetBasicContentAction $action
     * @return JsonResponse
     */
    public function getBasicContent(HomepageGetBasicContentAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
