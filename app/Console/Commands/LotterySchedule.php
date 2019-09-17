<?php

namespace App\Console\Commands;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LotterySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LotterySchedule {lottery_sign}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自开彩种自动开奖 argument --> lottery_sign';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $lotterySign = $this->argument('lottery_sign');
        if ($lotterySign !== null && is_string($lotterySign)) {
            $lotteryIssueEloq = LotteryIssue::where([
                ['lottery_id', $lotterySign],
                ['end_time', '<', time()],
            ])->with('lottery')->orderBy('end_time', 'desc')->first();
            if ($lotteryIssueEloq !== null) {
                $seriesList = LotterySerie::getList();
                $serieArr = $seriesList[$lotteryIssueEloq->lottery->series_id]; //当前彩种的系列Arr
                $splitter = $serieArr['encode_splitter']; //该彩种分割开奖号码的方式
                if ($lotteryIssueEloq !== null) {
                    $openCodeStr = LotteryIssue::getOpenNumber( //获取一个合法的随机开奖号码string
                        $lotteryIssueEloq->lottery->code_length,
                        $lotteryIssueEloq->lottery->valid_code,
                        $lotteryIssueEloq->lottery->lottery_type,
                        $splitter,
                        $lotteryIssueEloq->lottery->series_id
                    );
                    LotteryIssue::encode($lotterySign, $lotteryIssueEloq->issue, $openCodeStr);
                    // $lotteryIssueEloq->recordEncodeNumber($openCodeStr); //开始录号
                }
            }
        }
    }
}
