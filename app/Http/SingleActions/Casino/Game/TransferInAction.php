<?php
namespace App\Http\SingleActions\Casino\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Models\Casino\CasinoGameApiLog;

class TransferInAction
{
    protected $model;

    /**
     * @param  CasinoGameApiLog  $CasinoGameApiLog
     */
    public function __construct(CasinoGameApiLog $CasinoGameApiLog)
    {
        $this->model = $CasinoGameApiLog;
    }

    /**
     * transferIn
     * @param  CasinoApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(CasinoApiMainController $contll, array $inputDatas): JsonResponse
    {
        $returnVal  = [
            'api'       => 'GetBalance',
            'username'  => $contll->partnerUser->username,
            'user_id'   => $contll->partnerUser->id,
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
            $returnVal['param'] = json_encode($paramArr);       // 日志

            $paramStr       = http_build_query($paramArr);
            $paramEncode    = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey);

            $apiUrl = $contll->apiUrl . '/transferIn?' . $paramStr . '&param=' . $paramEncode;
            $returnVal['call_url'] = $apiUrl;                   // 日志

            $data   = $contll->request('GET', $apiUrl);

            $returnVal['return_content'] = json_encode($data);  // 日志

            if ($data['success']) {
                $this->model->saveItem($returnVal);
                \DB::commit();
                return $contll->msgOut(true, $data);
            }

            \DB::rollBack();
            $this->model->saveItem($returnVal);
            return $contll->msgOut(false, $data);
        } catch (\Exception $e) {
            \DB::rollBack();
            $returnVal['return_content'] = json_encode([$e->getMessage(),$e->getLine(),$e->getFile()]);  // 日志
            $this->model->saveItem($returnVal);

            return $contll->msgOut(false, [], '', '对不起, '.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
        }
    }
}
