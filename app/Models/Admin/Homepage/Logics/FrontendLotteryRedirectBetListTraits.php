<?php

namespace App\Models\Admin\Homepage\Logics;

use App\Lib\BaseCache;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;

trait FrontendLotteryRedirectBetListTraits
{
    use BaseCache;

    /**
     * 更新首页热门彩票缓存
     */
    public static function updatePopularLotteriesCache(): void
    {
        self::webPopularLotteriesCache();
        self::mobilePopularLotteriesCache();
    }

    /**
     * 更新 web端热门彩种缓存
     * @return array
     */
    public static function webPopularLotteriesCache(): array
    {
        $cacheKey = 'lottery_popular_lotteries_web';
        $lotteriesEloq = FrontendAllocatedModel::select('show_num', 'status')
            ->where('en_name', 'popular.lotteries.one')
            ->first();
        if ($lotteriesEloq === null) {
            $lotteriesEloq = FrontendAllocatedModel::createPopularLotteries();
        }
        return self::updateCache($cacheKey, $lotteriesEloq->show_num);
    }

    /**
     * 更新 app端热门彩种缓存
     * @return array
     */
    public static function mobilePopularLotteriesCache(): array
    {
        $cacheKey = 'lottery_popular_lotteries_app';
        $lotteriesEloq = FrontendAllocatedModel::select('show_num', 'status')
            ->where('en_name', 'mobile.popular.lotteries.one')
            ->first();
        if ($lotteriesEloq === null) {
            $lotteriesEloq = FrontendAllocatedModel::createMobilePopularLotteries();
        }
        return self::updateCache($cacheKey, $lotteriesEloq->show_num);
    }

    /**
     * @param  string $cacheKey
     * @param  int $showNum
     * @return array
     */
    public static function updateCache(string $cacheKey, $showNum): array
    {
        $dataEloq = self::select('id', 'lotteries_id', 'lotteries_sign')
            ->with(['lotteries:id,day_issue,en_name,cn_name,icon_path', 'issueRule:lottery_id,issue_seconds'])
            ->orderBy('sort', 'asc')
            ->limit($showNum)
            ->get();
        $datas = [];
        foreach ($dataEloq as $key => $dataIthem) {
            $datas[$key]['cn_name'] = $dataIthem->lotteries->cn_name ?? null;
            $datas[$key]['en_name'] = $dataIthem->lotteries->en_name ?? null;
            $datas[$key]['icon_path'] = $dataIthem->lotteries->icon_path ?? null;
            $datas[$key]['issue_seconds'] = $dataIthem->issueRule->first->issue_seconds ?? null;
            $datas[$key]['day_issue'] = $dataIthem->lotteries->day_issue ?? null;
        }
        self::saveTagsCacheData($cacheKey, $datas);
        return $datas;
    }
}
