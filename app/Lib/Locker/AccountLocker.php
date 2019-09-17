<?php namespace App\Lib\Locker;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * cache 必须支持 tags
 * Class AccountLocker
 * @package App\Lib
 */
class AccountLocker
{

    public static $tag = 'account_lock';

    // 缓存
    protected $memKey = '';
    protected $memValue = '';
    protected $prefix = 'account_lock_';

    protected $context = [];

    // 时间
    protected $cacheTimeout = 1;  // 分钟
    protected $lockerTimeout = 6; // 秒

    // 睡眠时间 目前支持微妙
    protected $sleepSeconds = 500000; // 500毫秒

    public function __construct($key, $cacheTimeout = 5, $lockerTimeout = 15, $sleep = 500000)
    {
        $this->memKey = $this->prefix.$key;
        $this->memValue = $key.'_'.date('Y-m-d H:i:s');
        $this->cacheTimeout = $cacheTimeout;
        $this->lockerTimeout = $lockerTimeout;
        $this->sleep = $sleep;
    }

    // 获取锁
    public function getLock()
    {
        $time = time();
        while (time() - $time < $this->lockerTimeout) {
            if (Cache::tags(self::$tag)->add($this->memKey, $this->memValue, $this->cacheTimeout)) {
                return true;
            }
            usleep($this->sleep);
        }
        Log::channel('log')->error('账户锁-获取锁失败-'.$this->memKey, $this->context);
        // 释放
        $this->release();
        return false;
    }

    // 释放当前
    public function release()
    {
        try {
            $ret = Cache::tags(self::$tag)->forget($this->memKey);
        } catch (\Exception $e) {
            Log::channel('log')->error('账户锁-释放锁失败-'.$e->getMessage(), $this->context);
            $ret = false;
        }
        return $ret;
    }

    // 上下文
    public function setContext($context)
    {
        $this->context = $context;
    }

    // 释放所有
    public static function releaseAll()
    {
        Cache::tags(self::$tag)->flush();
    }
}
