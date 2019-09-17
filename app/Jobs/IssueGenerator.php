<?php

namespace App\Jobs;

use App\Models\Game\Lottery\LotteryList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class IssueGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datas;

    /**
     * Create a new job instance.
     *
     * @param array $datas
     */
    public function __construct(array $datas)
    {
        $this->datas = $datas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $lotteryId = $this->datas['lottery_id'];
        $lottery = LotteryList::where('en_name', $lotteryId)->first();
        if (!$lottery) {
            Log::channel('issues')->error('游戏不存在');
        }
        $start_issue = $this->datas['start_issue'] ?? '';
        // 生成
        $res = $lottery->genIssue($this->datas['start_time'], $this->datas['end_time'], $start_issue);
        if ($res === true) {
            Log::channel('issues')->info('添加到 分开生成奖期队列完成');
        }
    }
}
