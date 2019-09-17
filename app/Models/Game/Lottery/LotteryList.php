<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\LotteryIssueGenerate;
use App\Models\Game\Lottery\Logics\LotteryLogics;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LotteryList extends BaseModel
{
    use LotteryIssueGenerate, LotteryLogics;

    /**
     * The attributes that was protected
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static $rules = [
        'cn_name' => 'required|min:4|max:32',
        'en_name' => 'required|min:4|max:32',
        'series_id' => 'required|min:2|max:32',
        'max_trace_number' => 'required|min:1|max:32',
        'issue_format' => 'required|min:2|max:32',
    ];

    public function issueRule(): hasMany
    {
        return $this->hasMany(LotteryIssueRule::class, 'lottery_id', 'en_name');
    }

    public function gameMethods(): HasMany
    {
        return $this->hasMany(LotteryMethod::class, 'lottery_id', 'en_name');
    }

    public function methodGroups()
    {
        return $this->hasMany(LotteryMethod::class, 'lottery_id', 'en_name')->select([
            'method_group',
            'status',
            'lottery_id'
        ])->groupBy('method_group');
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(LotterySerie::class, 'series_id', 'series_name');
    }

    public function basicways(): HasMany
    {
        return $this->hasMany(LotteryBasicWay::class, 'lottery_type', 'lottery_type');
    }

    //各个彩种最新一期的开奖
    public function specificNewestOpenedIssue(): hasOne
    {
        return $this->hasOne(LotteryIssue::class, 'lottery_id', 'en_name')->select(
            'lottery_id',
            'issue',
            'official_code',
            'encode_time'
        )->where('status_encode', 1)->orderBy('issue', 'desc');
    }
}
