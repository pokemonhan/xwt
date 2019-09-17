<?php

namespace App\Jobs;

use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class IssueInserter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datas;

    /**
     * Create a new job instance.
     *
     * @param  array  $datas
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
    public function handle()
    {
        try {
            if (!empty($this->datas)) {
                $message = 'started >>>>'.json_encode($this->datas, JSON_UNESCAPED_UNICODE)."\n";
                Log::channel('issues')->info($message);
                foreach ($this->datas as $data) {
                    $eloqLotteryIssue = LotteryIssue::where([
                        ['lottery_id', '=', $data['lottery_id']],
                        ['issue', '=', $data['issue']]
                    ])->first();
                    if ($eloqLotteryIssue === null) {
                        LotteryIssue::create($data);
                        $message = 'Finished >>>>'.json_encode($data, JSON_UNESCAPED_UNICODE)."\n";
                        Log::channel('issues')->info($message);
                    } else {
                        $message = 'Avoided Comflic data generation >>>>'.json_encode($data,JSON_UNESCAPED_UNICODE)."\n";
                        Log::channel('issues')->info($message);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::channel('issues')->error($e->getMessage());
        }
    }
}
