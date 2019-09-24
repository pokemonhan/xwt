<?php

namespace App\Http\Controllers\BackendApi\Admin\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\SingleActions\Backend\Admin\Payment\CallbackAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class CallbackController
 * @package App\Http\Controllers\BackendApi\Admin\Notice
 */
class CallbackController extends BackEndApiMainController
{
    /**
     * @var array $inputData 回调参数.
     */
    protected $inputData;

    /**
     * CallbackController constructor.
     * @param Request $request 请求.
     */
    public function __construct(Request $request)
    {
        //接收回调参数
        $this->inputData = $request->all() or $this->inputData = $request->input() or $this->inputData = $_REQUEST;
        if (empty($this->inputData)) {
            $this->inputData = [];
        }
        Log::channel('callback-data')->info($request->payment.'-'.json_encode($this->inputData));
        parent::__construct();
    }

    /**
     * @param integer        $direction 金流方向.
     * @param string         $payment   支付方式.
     * @param CallbackAction $action    处理器.
     * @return string
     * @throws \Exception 异常.
     */
    public function callback(int $direction, string $payment, CallbackAction $action) :string
    {
        return $action->execute($direction, $payment, $this->inputData);
    }
}
