<?php

namespace App\Models;

use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\Game\Lottery\ProjectHandleTrace;
use App\Models\Logics\ProjectTraits;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersAccount;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Project extends BaseModel
{
    use ProjectTraits;
    use ProjectHandleTrace;

    protected $guarded = ['id'];

    private const DEFAULT_PRIZE_GROUP = 1800;//默认奖金组

    public const STATUS_NORMAL = 0;
    public const STATUS_DROPED = 1;
    public const STATUS_LOST = 2;
    public const STATUS_WON = 3;
    public const STATUS_PRIZE_SENT = 4;
    public const STATUS_DROPED_BY_SYSTEM = 5;

    public const FROM_DESKTOP = 1;
    public const FROM_MOBILE = 2;
    public const FROM_OTHER = 3;

    public const STATUS_COMMISSION_WAIT = 0;
    public const STATUS_COMMISSION_PROCESSING = 1;
    public const STATUS_COMMISSION_PARTIAL = 2;
    public const STATUS_COMMISSION_FINISHED = 4;

    /**
     * @return HasOne
     */
    public function tracelist(): HasOne
    {
        return $this->hasOne(LotteryTraceList::class, 'project_id', 'id');
    }

    /**
     * left to right relationship
     *  related ya chi de table
     * through gya kan ne table
     * id lo chi de table ga id
     * user_id  lo chi de table ne chake ya me id
     * user_id let shi table ne chake ya me id
     * id  let shi table ne gya kan ne table ne chake ya me id
     * @return HasOneThrough
     */
    public function account(): HasOneThrough
    {
        return $this->hasOneThrough(
            FrontendUsersAccount::class,
            FrontendUser::class,
            'id',
            'user_id',
            'user_id',
            'id'
        );
    }

    public function lottery()
    {
        return $this->belongsTo(LotteryList::class, 'lottery_sign', 'en_name');
    }
}
