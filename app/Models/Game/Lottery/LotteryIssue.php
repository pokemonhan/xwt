<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\IssueCacheCalcLogics;
use App\Models\Game\Lottery\Logics\IssueEncodeLogics;
use App\Models\Game\Lottery\Logics\IssueLogics;
use App\Models\Game\Lottery\Logics\LotteryTrendCommonLogic;
use App\Models\Game\Lottery\Logics\SeriesLogic\IssueCalculateLogic;
use App\Models\Game\Lottery\Logics\LotteryTrendLogic;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotteryIssue extends BaseModel
{
    use IssueLogics;
    use IssueEncodeLogics;
    use IssueCalculateLogic;
    use LotteryTrendLogic;
    use LotteryTrendCommonLogic;
    use IssueCacheCalcLogics;

    /**
     * 中奖号码状态：等待开奖
     */
    public const ISSUE_CODE_STATUS_WAIT_CODE = 1;

    /**
     * 中奖号码状态：已输入号码，等待审核
     */
    public const ISSUE_CODE_STATUS_WAIT_VERIFY = 2;

    /**
     * 中奖号码状态：号码已审核
     */
    public const ISSUE_CODE_STATUS_FINISHED = 4;

    /**
     * 中奖号码状态：号码已取消开奖
     */
    public const ISSUE_CODE_STATUS_CANCELED = 8;

    /**
     * 中奖号码状态：提前开奖A，获取到开奖号码的时间早于官方理论开奖时间
     */
    public const ISSUE_CODE_STATUS_ADVANCE_A = 32;

    /**
     * 中奖号码状态：提前开奖B，获取到开奖号码的时间早于销售截止时间
     */
    public const ISSUE_CODE_STATUS_ADVANCE_B = 64;

    public const ENCODE_NONE = 0;
    public const ENCODED = 1;

    /**
     * 计奖状态
     */
    public const CALCULATE_NONE = 0;
    public const CALCULATE_PROCESSING = 1;
    public const CALCULATE_PARTIAL = 2;
    public const CALCULATE_FINISHED = 4;

    /**
     * 派奖状态
     */
    public const PRIZE_NONE = 0;
    public const PRIZE_PROCESSING = 1;
    public const PRIZE_PARTIAL = 2;
    public const PRIZE_FINISHED = 4;

    /**
     * 派佣金状态
     */
    public const COMMISSION_NONE = 0;
    public const COMMISSION_PROCESSING = 1;
    public const COMMISSION_PARTIAL = 2;
    public const COMMISSION_FINISHED = 4;

    /**
     * 追号单状态
     */
    public const TRACE_PRJ_NONE = 0;
    public const TRACE_PRJ_PROCESSING = 1;
    public const TRACE_PRJ_PARTIAL = 2;
    public const TRACE_PRJ_FINISHED = 4;
    
    protected $guarded = ['id'];

    public function lottery()
    {
        return $this->belongsTo(LotteryList::class, 'lottery_id', 'en_name');
        //->select('en_name', 'series_id')
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'issue', 'issue');
    }

    public function tracelists(): HasMany
    {
        return $this->hasMany(LotteryTraceList::class, 'issue', 'issue');
    }
}
