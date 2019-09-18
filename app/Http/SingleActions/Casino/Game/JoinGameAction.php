<?php
namespace App\Http\SingleActions\Casino\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Models\Casino\CasinoGameApiLog;

class JoinGameAction
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
     * 进入游戏
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

        try {
            $paramArr = [
                'username'          => $contll->username,
                'mainGamePlat'      => $inputDatas['mainGamePlat'],
                'accountUserName'   => $contll->partnerUser->username,
                'gamecode'          => $inputDatas['gamecode'],
                'demo'              => $inputDatas['demo'],
                'ip'                => real_ip(),
                'isMobile'          => $inputDatas['isMobile'],
            ];

            $returnVal['param'] = json_encode($paramArr);       // 日志

            $paramStr       = http_build_query($paramArr);
            $paramEncode    = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey);

            $apiUrl = $contll->apiUrl . '/joinGame?' . $paramStr . '&param=' . $paramEncode;

            $returnVal['call_url'] = $apiUrl;                   // 日志

            $data   = $contll->request('GET', $apiUrl);

            $returnVal['return_content'] = json_encode($data);  // 日志
            $this->model->saveItem($returnVal);

            if ($data['success']) {
                return $contll->msgOut(true, $data);
            }
            return $contll->msgOut(false, $data);
        } catch (\Exception $e) {
            $returnVal['return_content'] = json_encode([$e->getMessage(),$e->getLine(),$e->getFile()]);  // 日志
            $this->model->saveItem($returnVal);

            return $contll->msgOut(
                false,
                [],
                '',
                '对不起, '.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine()
            );
        }
    }
}
