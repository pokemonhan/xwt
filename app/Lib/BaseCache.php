<?php
namespace App\Lib;

use Exception;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait BaseCache
{
    /**
     * 获取缓存
     * @param $key
     * @return Repository
     * @throws Exception
     */
    // public static function getCacheData($key)
    // {
    //     $cacheConfig = self::getCacheConfig($key);
    //     if (isset($cacheConfig['tags'])) {
    //         return Cache::tags($cacheConfig['tags'])->get($cacheConfig['key'], []);
    //     }
    //     return Cache::get($cacheConfig['key'], []);
    // }

    /**
     * 获取tags缓存
     * @param  string  $key
     * @throws Exception
     */
    public static function getTagsCacheData(string $key)
    {
        $data = [];
        if (self::hasTagsCache($key)) {
            $cacheConfig = self::getCacheConfig($key);
            $data = Cache::tags($cacheConfig['tags'])->get($cacheConfig['key'], []);
        }

        return $data;
    }

    /**
     * 保存缓存
     * @param $key
     * @param $value
     * @throws Exception
     */
    // public static function saveCacheData($key, $value)
    // {
    //     $cacheConfig = self::getCacheConfig($key);
    //     if (isset($cacheConfig['tags'])) {
    //         if ($cacheConfig['expire_time'] <= 0) {
    //             Cache::tags($cacheConfig['tags'])->forever($cacheConfig['key'], $value);
    //         } else {
    //             $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
    //             Cache::tags($cacheConfig['tags'])->put($cacheConfig['key'], $value, $expireTime);
    //         }
    //     } else {
    //         if ($cacheConfig['expire_time'] <= 0) {
    //             Cache::forever($cacheConfig['key'], $value);
    //         } else {
    //             $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
    //             Cache::put($cacheConfig['key'], $value, $expireTime);
    //         }
    //     }
    // }

    /**
     * 保存tags缓存
     * @param  string $key
     * @param  mixed $value
     * @throws Exception
     */
    public static function saveTagsCacheData(string $key, $value)
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

    /**
     * 删除缓存
     * @param $key
     * @return bool
     * @throws Exception
     */
    // public static function mtsFlushCache($key): bool
    // {
    //     $cacheConfig = self::getCacheConfig($key);
    //     if(!empty($cacheConfig) && isset($cacheConfig['key'])){
    //         return Cache::forget($cacheConfig['key'], []);
    //     }else{
    //         return false;
    //     }
    // }

    /**
     * 删除tags缓存
     * @param  string $key
     * @return bool
     * @throws Exception
     */
    public static function deleteTagsCache(string $key): bool
    {
        $cacheConfig = self::getCacheConfig($key);
        if (!empty($cacheConfig) && isset($cacheConfig['tags'], $cacheConfig['key'])) {
            return Cache::tags($cacheConfig['tags'])->forget($cacheConfig['key']);
        } else {
            return false;
        }
    }

    /**
     * 获取缓存配置
     * @param  string $key
     * @return mixed
     */
    public static function getCacheConfig(string $key)
    {
        $cacheConfig = config('web.main.cache');
        return $cacheConfig[$key] ?? [];
    }

    /**
     * 检查是否存在缓存
     * @param $key
     * @return bool
     * @throws Exception
     */
    // public static function hasCache($key): bool
    // {
    //     $cacheConfig = self::getCacheConfig($key);
    //     if (isset($cacheConfig['tags'])) {
    //         return Cache::tags($cacheConfig['tags'])->has($cacheConfig['key']);
    //     }
    //     return Cache::has($cacheConfig['key']);
    // }

    /**
     * 检查是否存在tags缓存
     * @param  string $key
     * @return bool
     * @throws Exception
     */
    public static function hasTagsCache(string $key): bool
    {
        $cacheConfig = self::getCacheConfig($key);
        if (!empty($cacheConfig) && isset($cacheConfig['tags'], $cacheConfig['key'])) {
            return Cache::tags($cacheConfig['tags'])->has($cacheConfig['key']);
        } else {
            return false;
        }
    }

    /**
     * @param  string  $picStr
     * @param  string  $delimiter
     * @return void
     */
    public static function deleteCachePic(string $picStr, string $delimiter = ''): void
    {
        $redisKey = 'cleaned_images';
        $cleanedImages = self::getTagsCacheData($redisKey);
        if ($delimiter === '') {
            $picArr = $picStr;
        } else {
            $picArr = explode($delimiter, $picStr);
        }
        foreach ((array) $picArr as $picName) {
            $picName = (string) $picName;
            $cleanedImages = (array) $cleanedImages;
            if (array_key_exists($picName, $cleanedImages)) {
                unset($cleanedImages[$picName]);
            }
        }
        self::saveTagsCacheData($redisKey, $cleanedImages);
    }
}
