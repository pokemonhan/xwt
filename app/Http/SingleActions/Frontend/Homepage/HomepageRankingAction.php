<?php

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class HomepageRankingAction
{
    protected $model;

    /**
     * @param  Project  $projectModel
     */
    public function __construct(Project $projectModel)
    {
        $this->model = $projectModel;
    }

    /**
     * 首页中奖排行榜
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        // $rankingEloq = FrontendAllocatedModel::select('status', 'show_num')->where('en_name', 'winning.ranking')->first();
        // if ($rankingEloq === null || $rankingEloq->status !== 1) {
        //     return $contll->msgOut(false, [], '100400');
        // }
        // if (Cache::has('homepage_ranking')) {
        //     $rankingData = Cache::get('homepage_ranking');
        // } else {
        //     $rankingData = $this->model::select('username', 'lottery_sign', 'bonus')->where('bonus', '>', '0')->orderBy('bonus', 'DESC')->limit($rankingEloq->show_num)->get()->toArray();
        //     $expiresAt = Carbon::now()->addHours(1); //缓存1小时
        //     Cache::put('homepage_ranking', $rankingData, $expiresAt);
        // }

        //先使用假数据展示  需要改成真实数据
        //###########################################
        $FrontendAllocatedEloq = FrontendAllocatedModel::where('en_name','winning.ranking')->first();
        $rankingData = config('game.ranking');
        if (($FrontendAllocatedEloq !== null) && is_integer($FrontendAllocatedEloq->show_num)) {
            $rankingData = array_slice($rankingData,0, $FrontendAllocatedEloq->show_num);
        }
        return $contll->msgOut(true, $rankingData);
    }
}
