<?php

namespace App\Models;

use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Logics\TraceTraits;

class LotteryTrace extends BaseModel
{
    use TraceTraits;
    protected $guarded = ['id'];

    public const STATUS_RUNNING = 0;
    public const STATUS_FINISHED = 1;
    public const STATUS_WIN_STOPED = 2;
//    public const STATUS_ADMIN_CANCELED = 3;
    public const STATUS_SYSTEM_CANCELED = 4;
    public const STATUS_USER_DROPED = 5;


    public function traceRunningLists()
    {
        return $this->hasMany(LotteryTraceList::class, 'trace_id', 'id')->where('status', 0);
    }

    public function traceLists()
    {
        return $this->hasMany(LotteryTraceList::class, 'trace_id', 'id');
    }
    
    public function lottery()
    {
        return $this->belongsTo(LotteryList::class, 'lottery_sign', 'en_name');
    }
}
