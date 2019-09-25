<?php
namespace App\Models\Casino;

use App\Models\Casino\Cache\CasinoGameCache;
use Illuminate\Support\Facades\DB;

/**
 * Class CasinoGameList
 * @package App\Models\Casino
 */
class CasinoGameList extends BaseCasinoModel
{
    /**
     * @param array $data Data.
     * @return string
     */
    public function saveItemAll(array $data)
    {
        DB::beginTransaction();
        foreach ($data as $dataItem) {
            if (!self::find($dataItem['id'])) {
                unset($dataItem['deleted_at']);
                if (!self::insert($dataItem)) {
                    DB::rollBack();
                    return 0;
                }
            }
        }
        DB::commit();
        return 1;
    }

    /**
     * @param integer $id ID.
     * @return integer
     */
    public function getItem(int $id)
    {
        $listData = self::where('id', $id)->first();
        if (!$listData) {
            $this->errMsg = '数据不存在';
            return 1;
        }
        return $listData;
    }

    /**
     * @return array
     */
    public function getHomeList()
    {
        // 获取所有的类型
        $categoryArr    = [];
        $categoryName   = [];
        $categories     = CasinoCategories::where('home', 1)->get();

        foreach ($categories as $v) {
            $categoryArr[]          = $v->code;
            $categoryName[$v->code] = $v->name;
        }

        $listModel = self::where('status', 1)->where('able_recommend', 0)->whereIN('category', $categoryArr)->skip(0)->take(100)->get();
        $data = [];

        foreach ($listModel as $v) {
            $data[$categoryName[$v->category]][] = $v->toArray();
        }
        return $data;
    }
    
    /**
     * 更新 web端热门彩种缓存
     * @return array
     */
    public static function webCasinoCache(): array
    {
        $cacheKey   = 'casino_popular_web';
        // 首页推荐游戏类型
        $cateGorieHome  = CasinoGameCategorie::where('home', 1)->get(['code','name']);
        $categoryIn     = [];
        foreach ($cateGorieHome as $item) {
            $categoryIn[] = $item->code;
        }
        $casinoPlatElog = self::whereIn('category', $categoryIn)->get();

        return self::updateCache($cacheKey, $casinoPlatElog, $cateGorieHome);
    }

    /**
     * @param string $cacheKey      缓存key.
     * @param object $dataEloq      所欲游戏.
     * @param object $cateGorieHome 游戏类型.
     * @return array
     */
    public static function updateCache(string $cacheKey, object $dataEloq, object $cateGorieHome): array
    {
        $datas = [];
        $maxCache = 6;
        $cacheNum = [];
        foreach ($dataEloq as $key => $dataIthem) {
            foreach ($cateGorieHome as $itemCode) {
                $cateCode = $itemCode->code;
                if ($dataIthem->category === $cateCode) {
                    if (!empty($cacheNum[$cateCode]) && $cacheNum[$cateCode] >= $maxCache) {
                        continue;
                    }
                    $datas[$cateCode]['cateGorie']      = $itemCode->name;
                    $datas[$cateCode][$key]['cn_name']  = $dataIthem->cn_name ?? null;
                    $datas[$cateCode][$key]['en_name']  = $dataIthem->en_name ?? null;
                    $datas[$cateCode][$key]['icon_path'] = config('casino.game_img_url') . $dataIthem->img ?? null;
                    $datas[$cateCode][$key]['game_code'] = $dataIthem->pc_game_code ?? $dataIthem->mobile_game_code;
                    $datas[$cateCode][$key]['main_game_plat_code'] = $dataIthem->main_game_plat_code ?? null;

                    if (!empty($cacheNum[$cateCode])) {
                        $cacheNum[$cateCode] = $cacheNum[$cateCode] + 1;
                    } else {
                        $cacheNum[$cateCode] = 1;
                    }
                }
            }
        }
        CasinoGameCache::saveTagsCacheData($cacheKey, $datas);
        return $datas;
    }
}
