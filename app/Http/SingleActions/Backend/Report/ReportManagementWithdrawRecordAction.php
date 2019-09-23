<?php


namespace App\Http\SingleActions\Backend\Report;

use App\Http\Controllers\BackendApi\Report\ReportManagementController;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersBankCard;
use App\Models\User\UsersRegion;
use App\Models\User\UsersWithdrawHistorie;
use Illuminate\Http\JsonResponse;

class ReportManagementWithdrawRecordAction
{
    public const DEFAULT_CHANNEL = 'person';
    protected $hiddenField = [
        'user_id',
        'parent_id',
        'rid',
        'updated_at',
        'bank_sign',
        'real_amount',
        'request_time',
        'expire_time',
        'process_time',
        'process_day',
        'source',
        'client_ip',
        'description',
        'desc',
        'admin_id',
    ];
    protected $big_money = 10000; //大额限制
    protected $listDatas = [ //列表数据需要的字段
        'username', //用户名
        'is_tester', //是否是测试用户
        'top_username', //所属总代
        'top_id', //所属总代
        'created_at', //申请时间
        'amount', //提现金额
        'is_big_money', //是否大额
        'card_number', //卡号
        'card_username', //户名
        'bank_name', //银行名称
        'province_id', //省份id
        'province_name', //省份名称
        'branch', //开户行
        'claimant', //认领人
        'claim_time', //认领时间
        'audit_manager', //审核管理员
        'remittance_amount', //实际汇款金额
        'status', //状态
        'order_id', //编号
        'order_no', //参考订单号
        'ct_order_at', //订单发起时间
        'up_order_at', //订单确认时间
        'channel_sign', //提款渠道
    ];
    protected $usersWithdrawHistoriesModel;
    public function __construct(UsersWithdrawHistorie $usersWithdrawHistorie)
    {
        $this->usersWithdrawHistoriesModel = $usersWithdrawHistorie;
    }

    public function execute(ReportManagementController $contll, array $inputDatas) :JsonResponse
    {
        try {
            $searchAbleFields = ['username', 'order_id', 'is_tester'];
            if (isset($inputDatas['status'])) {
                array_push($searchAbleFields, 'status');
            }
            $orderFields = 'id';
            $orderFlow = 'desc';
            $listDatas = $contll->generateSearchQuery($this->usersWithdrawHistoriesModel, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
            $listDatas = $this->assemblyData($listDatas); //组装数据
            return $contll->msgOut(true, $listDatas);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], '400', '系统错误');
        }
    }

    /**
     * 组装列表所需要的数据
     * @param mixed $listDatas
     * @return object
     */
    private function assemblyData($listDatas) :object
    {
        $frontendUserModel = new FrontendUser();
        $frontendUserBankModel = new FrontendUsersBankCard();
        $usersRegionModel = new UsersRegion();
        foreach ($listDatas as $key1 => $val1) {
            $withdrawHistoryOptModel = $val1->withdrawHistoryOpt;
            $listDatas[$key1]->is_big_money = $val1->amount > $this->big_money ? 1 : 0;
            $listDatas[$key1]->top_username = $frontendUserModel->select('username')->where('id', $val1->top_id)->first()->username ?? null;
            $bankInfo = $frontendUserBankModel->where('id', $val1->card_id)->first();
            $listDatas[$key1]->bank_name = $bankInfo->bank_name ?? null;
            $listDatas[$key1]->province_id = $bankInfo->province_id ?? null;
            $listDatas[$key1]->province_name = $usersRegionModel->select('region_name')->where('id', $bankInfo->province_id)->first()->region_name ?? null;
            $listDatas[$key1]->branch = $bankInfo->branch??null;
            $listDatas[$key1]->claimant = $withdrawHistoryOptModel->claimant??null;
            $listDatas[$key1]->claim_time = $withdrawHistoryOptModel->claim_time??null;
            $listDatas[$key1]->audit_manager = $withdrawHistoryOptModel->audit_manager??null;
            $listDatas[$key1]->remittance_amount = $withdrawHistoryOptModel->remittance_amount??null;
            $listDatas[$key1]->status = $withdrawHistoryOptModel->status??$this->usersWithdrawHistoriesModel::STATUS_AUDIT_WAIT;
            $listDatas[$key1]->order_no = $withdrawHistoryOptModel->order_no??null;
            $listDatas[$key1]->ct_order_at = $withdrawHistoryOptModel->created_at->format('Y-m-d H:i:s')??null;
            $listDatas[$key1]->up_order_at = $withdrawHistoryOptModel->updated_at->format('Y-m-d H:i:s')??null;
            $listDatas[$key1]->channel_sign = $withdrawHistoryOptModel->channel_sign??self::DEFAULT_CHANNEL;
            unset($val1->withdrawHistoryOpt);
            //隐藏前端不需要的字段
            $tmpVal = $val1->toArray();
            foreach ($tmpVal as $key2 => $val2) {
                if (in_array($key2, $this->hiddenField)) {
                    unset($listDatas[$key1]->$key2);
                }
                unset($val2);
            }
        }
        return  $listDatas;
    }
}
