<?php
namespace App\Http\SingleActions\Casino\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Models\Casino\CasinoGameApiLog;

/**
 * Class TransferInAction
 * @package App\Http\SingleActions\Casino\Game
 */
class TransferInAction
{
    /**
     * @var CasinoGameApiLog
     */
    protected $model;

    /**
     * @param  CasinoGameApiLog $CasinoGameApiLog Log.
     */
    public function __construct(CasinoGameApiLog $CasinoGameApiLog)
    {
        $this->model = $CasinoGameApiLog;
    }

    /**
     * transferIn
     * @param  CasinoApiMainController $contll     基类.
     * @param  array                   $inputDatas 参数.
     * @return string
     */
    public function execute(CasinoApiMainController $contll, array $inputDatas)
    {
        $returnVal  = [
            'api'       => 'GetBalance',
            'username'  => $contll->partnerUser->username ?? 'lin1',
            'user_id'   => $contll->partnerUser->id ?? 16,
            'ip'        => real_ip(),
        ];

        // 帐变
        \DB::beginTransaction();
        try {
            // 1帐变处理
            $user = $contll->partnerUser;
            if ($user->account()->exists()) {
                $account = $user->account;
            } else {
                return $contll->msgOut(false, [], '100313');
            }
            $params = [
                'user_id' => $user->id,
                'amount' => $inputDatas['price'],
            ];

            $resStatus = $account->operateAccount($params, 'casino_in');

            if ($resStatus !== true) {
                \DB::rollBack();
                return $contll->msgOut(false, [], '', $resStatus);
            }

            // 2 api请求
            $paramArr = [
                'username'          => $contll->username,
                'mainGamePlat'      => $inputDatas['mainGamePlat'],
                'accountUserName'   => $contll->partnerUser->username,
                'price'             => $inputDatas['price'],
            ];
            $returnVal['params'] = json_encode($paramArr);       // 日志

            $paramStr       = http_build_query($paramArr);
            $paramEncode    = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey);

            $apiUrl = $contll->apiUrl . '/transferIn?' . $paramStr . '&param=' . $paramEncode;
            $returnVal['call_url'] = $apiUrl;                   // 日志

            $data   = $contll->request('GET', $apiUrl);
            $dataDe = json_decode($data, 1);

            $returnVal['return_content'] = $data;  // 日志

            if (!empty($dataDe) && $dataDe['success']) {
                $this->model->saveItem($returnVal);
                \DB::commit();
                return $contll->msgOut(true, $dataDe);
            }

            \DB::rollBack();
            $this->model->saveItem($returnVal);
            return $contll->msgOut(false, [$data]);
        } catch (\Exception $e) {
            \DB::rollBack();
            $returnVal['return_content'] = json_encode([$e->getMessage(),$e->getLine(),$e->getFile()]);  // 日志
            $this->model->saveItem($returnVal);

            return $contll->msgOut(false, [], '', '对不起, '.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
        }
    }
}
