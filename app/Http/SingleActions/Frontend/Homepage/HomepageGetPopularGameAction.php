<?php

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\BaseCache;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use App\Models\Game\ChessCards\FrontendPopularChessCardsList;
use App\Models\Game\EGame\FrontendPopularEGameList;
use Illuminate\Http\JsonResponse;

class HomepageGetPopularGameAction
{
    use BaseCache;

    protected $model;

    /**
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 获取热门游戏列表
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = [];

        //热门彩种
        $lotteryRedisKey = 'homepage_popular_lotteries';
        $data['lotteries'] = self::getTagsCacheData($lotteryRedisKey);
        if (empty($data['lotteries'])) {
            $data['lotteries'] = FrontendLotteryRedirectBetList::webPopularLotteriesCache();
        }

        //热门棋牌
        $chessCardsRedisKey = 'homepage_chess_cards';
        $data['chess_cards'] = self::getTagsCacheData($chessCardsRedisKey);
        if (empty($data['chess_cards'])) {
            $data['chess_cards'] = FrontendPopularChessCardsList::select('chess_card_id', 'name', 'icon')
                ->orderBy('sort', 'asc')
                ->get()
                ->toArray();
            self::saveTagsCacheData($chessCardsRedisKey, $data['chess_cards']);
        }

        //热门电子
        $eGameRedisKey = 'homepage_e_game';
        $data['e_game'] = self::getTagsCacheData($eGameRedisKey);
        if (empty($data['e_game'])) {
            $data['e_game'] = FrontendPopularEGameList::select(
                'computer_game_id',
                'name',
                'icon'
            )->orderBy('sort', 'asc')
            ->get()
            ->toArray();
            self::saveTagsCacheData($chessCardsRedisKey, $data['e_game']);
        }

        return $contll->msgOut(true, $data);
    }
}
