<?php

namespace App\Http\Controllers\FrontendApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Homepage\HomepageReadMessageRequest;
use App\Http\Requests\Frontend\Homepage\HomePageNoticeRequest;
use App\Http\SingleActions\Frontend\Homepage\HomepageActivityListAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageGetBasicContentAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageGetPopularGameAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageGetWebInfoAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageNoticeAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageRankingAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageReadMessageAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageShowHomepageModelAction;
use App\Http\SingleActions\Frontend\Homepage\HompageActivityAction;
use App\Http\SingleActions\Frontend\Homepage\HompageBannerAction;
use App\Http\SingleActions\Frontend\Homepage\HompageCasinoGameAction;
use App\Http\SingleActions\Frontend\Homepage\HompageLotteryNoticeListAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularLotteriesAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularMethodsAction;
use Illuminate\Http\JsonResponse;

/**
 * Class HomepageController
 * @package App\Http\Controllers\FrontendApi\Homepage
 */
class HomepageController extends FrontendApiMainController
{
    /**
     * @var integer
     */
    private $bannerFlag = 1; //网页端banner

    /**
     * 需要展示的前台模块
     * @param  HomepageShowHomepageModelAction $action F.
     * @return JsonResponse
     */
    public function showHomepageModel(HomepageShowHomepageModelAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页轮播图列表
     * @param  HompageBannerAction $action F.
     * @return JsonResponse
     */
    public function banner(HompageBannerAction $action): JsonResponse
    {
        return $action->execute($this, $this->bannerFlag);
    }

    /**
     * 热门彩票一
     * @param  HompagePopularLotteriesAction $action F.
     * @return JsonResponse
     */
    public function popularLotteries(HompagePopularLotteriesAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门彩票二-玩法
     * @param  HompagePopularMethodsAction $action F.
     * @return JsonResponse
     */
    public function popularMethods(HompagePopularMethodsAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 首页热门游戏--娱乐城
     * @param  HompageCasinoGameAction $action F.
     * @return JsonResponse
     */
    public function casinoGame(HompageCasinoGameAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 热门活动
     * @param  HompageActivityAction $action F.
     * @return JsonResponse
     */
    public function activity(HompageActivityAction $action): JsonResponse
    {
        return $action->execute($this, 1);
    }

    /**
     * @param HomePageNoticeRequest $request 参数.
     * @param HomepageNoticeAction  $action  F.
     * @return JsonResponse
     */
    public function notice(HomePageNoticeRequest $request, HomepageNoticeAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 站内信 已读处理
     * @param  HomepageReadMessageRequest $request 参数.
     * @param  HomepageReadMessageAction  $action  F.
     * @return JsonResponse
     */
    public function readMessage(HomepageReadMessageRequest $request, HomepageReadMessageAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 首页中奖排行榜
     * @param  HomepageRankingAction $action F.
     * @return JsonResponse
     */
    public function ranking(HomepageRankingAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 开奖公告列表
     * @param  HompageLotteryNoticeListAction $action F.
     * @return JsonResponse
     */
    public function lotteryNoticeList(HompageLotteryNoticeListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 活动列表
     * @param HomepageActivityListAction $action F.
     * @return JsonResponse
     */
    public function activityList(HomepageActivityListAction $action): JsonResponse
    {
        $inputDatas['type'] = '1';
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取网站基本信息
     * @param  HomepageGetWebInfoAction $action F.
     * @return JsonResponse
     */
    public function getWebInfo(HomepageGetWebInfoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取首页基本内容
     * @param  HomepageGetBasicContentAction $action F.
     * @return JsonResponse
     */
    public function getBasicContent(HomepageGetBasicContentAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取热门游戏列表
     * @param HomepageGetPopularGameAction $action F.
     * @return JsonResponse
     */
    public function getPopularGame(HomepageGetPopularGameAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
