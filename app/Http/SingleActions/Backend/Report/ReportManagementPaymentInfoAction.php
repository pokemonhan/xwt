<?php

namespace App\Http\SingleActions\Backend\Report;

use App\Http\Controllers\BackendApi\Report\ReportManagementController;
use App\Models\Pay\PaymentInfo;

/**
 * Class ReportManagementPaymentInfoAction
 * @package App\Http\SingleActions\Backend\Report
 */
class ReportManagementPaymentInfoAction
{
    /**
     * @var object model
     */
    protected $model;

    /**
     * ReportManagementPaymentInfoAction constructor.
     * @param PaymentInfo $backendPaymentInfo 模型.
     */
    public function __construct(PaymentInfo $backendPaymentInfo)
    {
        $this->model = $backendPaymentInfo;
    }

    /**
     * @param ReportManagementController $contll 自己的控制器.
     * @return mixed
     */
    public function execute(ReportManagementController $contll)
    {
        $payments = (new $this->model())->select('payment_name', 'payment_sign')->get();
        $types = config('pay.pay_type');
        $output = [
            'payments' => $payments,
            'types' => $types,
        ];
        return $contll->msgOut(true, $output);
    }
}
