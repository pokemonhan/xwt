<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\LotteryTrace;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Game\Lottery\Logics\LotteryTraceListLogics;
use App\Models\User\FrontendUser;

class LotteryTraceList extends BaseModel
{
    use LotteryTraceListLogics;
    
    protected $guarded = ['id'];
    public const STATUS_WAITING = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_FINISHED = 2;
    public const STATUS_USER_STOPED = 3;
    public const STATUS_ADMIN_STOPED = 4;
    public const STATUS_SYSTEM_STOPED = 5;
    public const STATUS_WIN_STOPED = 6;

    public function trace(): BelongsTo
    {
        return $this->belongsTo(LotteryTrace::class, 'trace_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(FrontendUser::class, 'id', 'user_id');
    }
}
