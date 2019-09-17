<?php
namespace App\Models\Logics ;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UserCommissions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use Illuminate\Support\Carbon;

trait UserCommissionsTrait
{


    /**
     * 计算返点和佣金
     * @param int $projectId
     * @return bool
     */
    public static function calculateCommissions(int $projectId) : bool
    {
        $projectInfo = Project::where([
            ['id','=',$projectId],
            ['status_commission','=',Project::STATUS_COMMISSION_WAIT]
        ])
            ->whereIn('status', [2,3,4])
            ->first();

        if (!$projectInfo) {
            Log::channel('commissions')->error('projectId:'.$projectId.' 不存在或者返点状态不对，无法计算返点');
            return false ;
        }

        //计算返点
        $aBasicData = [
            'project_id' => $projectInfo->id,
            'lottery_sign' => $projectInfo->lottery_sign,
            'issue' => $projectInfo->issue,
        ];

        $iClassicAmount = 2000 ;

        //投注用户信息obj
        $betUser = FrontendUser::find($projectInfo->user_id);


        //***************计算上级佣金 start*****************//
        //所有上级array
        $aFores = $betUser->rid ? array_reverse(explode('|', $betUser->rid)) : [];

//        $diffForePrize = 0;

        foreach ($aFores as $i => $iForeId) {
            $oFores[$iForeId] =  FrontendUser::find($iForeId);
            if ($i === 0) {
                $iLastId = $projectInfo->user_id;
            }

            //上级比自己高出的奖金组
//            if ($i === (count($aFores) -1)) {
//                $diffForePrize = $oFores[$iForeId]->prize_group - $betUser->prize_group;
//            }
        }


        $aCommissions = [];

        foreach ($oFores as $iForeId => $oFore) {
            if ($oFore->prize_group > $betUser->prize_group) {
                if ($iLastId === $projectInfo->user_id) {
                    $iLastPrizeGroup = $betUser->prize_group;
                } else {
                    $iLastPrizeGroup =  $oFores[$iLastId]->prize_group;
                }

                $aCommissionData = [
                        'user_id' => $iForeId,
                        'account_id' => $oFore->account_id,
                        'username' => $oFore->username,
                        'rid' => $oFore->rid,
                        'is_tester' => $oFore->is_tester,
                        'bet_amount' => $projectInfo->total_cost,
                        'amount' => $projectInfo->total_cost * ($oFore->prize_group - $iLastPrizeGroup)/$iClassicAmount,
                    ];

                if ($oFore->prize_group === $iLastPrizeGroup) {
                    break;
                }

                if ($aCommissionData['amount'] <= 0) {
                    break;
                }

                    $aCommissions = array_merge($aBasicData, $aCommissionData);

                    self::updateUserCommissions($aCommissions);

                    //break; //只给上级返点
            }
            $iLastId = $iForeId;
        }
        //***************计算上级佣金 end*****************//

        //计算自己的返点
        if ($betUser->prize_group > $projectInfo->bet_prize_group) {
            $aCommissionData = [
                'user_id' => $betUser->id,
                'account_id' => $betUser->account_id,
                'username' => $betUser->username,
                'rid' => $betUser->rid,
                'is_tester' => $betUser->is_tester,
                'bet_amount' => $projectInfo->total_cost,
                'amount' => $projectInfo->total_cost
                    * ($betUser->prize_group - $projectInfo->bet_prize_group)
                    /$iClassicAmount,
            ];
            $aCommissions = array_merge($aBasicData, $aCommissionData);
            self::updateUserCommissions($aCommissions);
        }

        Log::channel('commissions')->info('projectId:'.$projectId.'  计算存储佣金/返点完成');
        return true ;
    }

    /**
     * 计算并发放返点
     * @param int $projectId
     * @return bool
     */
    public static function sendCommissions(int $projectId)
    {
        if (self::calculateCommissions($projectId) === false) {
            Log::channel('commissions')->info($projectId.'计算返点失败');
            return false;
        }

        $userCommissions = UserCommissions::where([
            ['project_id', '=', $projectId],
            ['status', '=', UserCommissions::STATUS_WAIT]
        ])->get();


        //如果没有返点数据记录
        if (!$userCommissions->count()) {
            self::setProjectCommissionSentStatus($projectId);
            self::setIssueCommissionStatus($projectId);

            Log::channel('commissions')->info($projectId.'没有返点数据记');
            return true ;
        }


        $bSuccess = true;

        foreach ($userCommissions as $oCommission) {
            if ($oCommission->amount <= 0) {
                self::setToSent($oCommission->id);
                continue;
            }

            DB::beginTransaction();

            if (self::send($oCommission->id)) {
                DB::commit();
            } else {
                $bSuccess = false;
                DB::rollBack();
                Log::channel('commissions')->info('projectId:'.$projectId.' 佣金/返点发放失败，已回滚');
            }
        }

        if ($bSuccess) {
            self::setProjectCommissionSentStatus($projectId);
            self::setIssueCommissionStatus($projectId);
            Log::channel('commissions')->info('projectId:'.$projectId.' 佣金/返点发放完成');
        }

        return $bSuccess;
    }

    private static function updateUserCommissions(array $data) : bool
    {
        if ($data['user_id'] && $data['project_id']) {
            $row = UserCommissions::where([
                ['user_id', $data['user_id']],
                ['project_id', $data['project_id']]
            ])->first();

            if (empty($row)) {
                return (bool)UserCommissions::create($data);
            } else {
                return (bool)$row->update($data);
            }
        }
    }

    /**
     * 设置返点状态为已完成
     * @param int $ProjectId
     * @return bool
     */
    private static function setProjectCommissionSentStatus(int $ProjectId) : bool
    {
        $aConditions = [
            ['id' ,'=', $ProjectId],
            ['status_commission' ,'=', Project::STATUS_COMMISSION_WAIT]
        ];

        $data = [
            'status_commission' => Project::STATUS_COMMISSION_FINISHED,
            'commission_time' => time()
        ];

        return Project::where($aConditions)->update($data) ;
    }

    /**
     * 设置奖期表中的返点状态为完成
     * @param int $projectId
     * @return bool
     */
    private static function setIssueCommissionStatus(int $projectId) : bool
    {
        $oProject = Project::find($projectId);

        $aConditions = [
            ['lottery_id', '=', $oProject->lottery_sign],
            ['issue', '=', $oProject->issue]
        ];

        $data = [
            'status_commission' => LotteryIssue::COMMISSION_FINISHED,
            'commission_time' => time()
        ];

        return LotteryIssue::where($aConditions)->update($data);
    }

    /**
     * 设置返点表中的状态为完成
     * @param int $commissionId
     * @return bool
     */

    private static function setToSent(int $commissionId) : bool
    {
        $aConditions = [
            ['id', '=',$commissionId],
            ['status', '=',UserCommissions::STATUS_WAIT],
        ];
        $data        = [
            'status'  => UserCommissions::STATUS_DONE,
            'sent_at' => Carbon::now()->toDateTimeString()
        ];
        return UserCommissions::Where($aConditions)->update($data) ;
    }


    /**
     * 发放返点、佣金
     * @param int $commissionId
     * @return bool
     */
    public static function send(int $commissionId) : bool
    {
        $commissionInfo = UserCommissions::find($commissionId) ;
        $projectInfo = Project::find($commissionInfo->project_id);

        $params = [
            'user_id' => $commissionInfo->user_id,
            'amount' => $commissionInfo->amount,
            'project_id'=>$commissionInfo->project_id,
            'lottery_id'=>$projectInfo->lottery_sign,
            'issue'=>$projectInfo->issue,
        ];

        $account  = FrontendUsersAccount::where('user_id', $commissionInfo->user_id)->first();

        $accountType = $commissionInfo->user_id === $projectInfo->user_id ? 'bet_commission' : 'commission' ;

        if ($account->operateAccount($params, $accountType)) {
            return self::setToSent($commissionId);
        } else {
            return  false ;
        }
    }
}
