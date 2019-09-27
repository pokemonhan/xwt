<?php
namespace App\Models\Casino\Cache;

use App\Lib\BaseCache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Class CasinoGameCache
 * @package App\Models\Casino\Cache
 */
class CasinoGameCache
{
    use BaseCache;

    /**
     * 保存tags缓存
     * @param  string $key   Key.
     * @param  array  $value Val.
     * @throws Exception
     */
    public static function saveTagsCacheData(string $key, array $value)
    {
        $cacheConfig = self::getCacheConfig($key);
        if (!empty($cacheConfig) && isset($cacheConfig['tags'], $cacheConfig['key'])) {
            if ($cacheConfig['expire_time'] <= 0) {
                Cache::tags($cacheConfig['tags'])->forever($cacheConfig['key'], $value);
            } else {
                $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
                Cache::tags($cacheConfig['tags'])->put($cacheConfig['key'], $value, $expireTime);
            }
        }
    }
}