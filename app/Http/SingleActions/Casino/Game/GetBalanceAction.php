<?php
namespace App\Http\SingleActions\Casino\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Models\Casino\CasinoGameApiLog;

/**
 * Class GetBalanceAction
 * @package App\Http\SingleActions\Casino\Game
 */
class GetBalanceAction
{
    /**
     * @var CasinoGameApiLog
     */
    protected $model;

    /**
     * @param  CasinoGameApiLog $CasinoGameApiLog 日志.
     */
    public function __construct(CasinoGameApiLog $CasinoGameApiLog)
    {
        $this->model = $CasinoGameApiLog;
    }

    /**
     * 查询余额
     * @param  CasinoApiMainController $contll     娱乐城基类.
     * @param  array                   $inputDatas 参数.
     * @return string
     */
    public function execute(CasinoApiMainController $contll, array $inputDatas)
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
            ];

            $returnVal['params'] = json_encode($paramArr);       // 日志

            $paramStr           = http_build_query($paramArr);
            $paramEncode        = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey, 0);

            $apiUrl = $contll->apiUrl . '/getBalance?' . $paramStr . '&param=' . urlencode($paramEncode);

            $returnVal['call_url'] = $apiUrl;                   // 日志

            $data   = $contll->request('GET', $apiUrl, [], '', 0, 0, 0);
            $data1  = json_decode($data, 1);

            $returnVal['return_content'] = $data;  // 日志
            $this->model->saveItem($returnVal);

            if ($data1['success']) {
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
