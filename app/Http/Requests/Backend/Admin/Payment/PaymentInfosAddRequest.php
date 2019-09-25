<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentInfosAddRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentInfosAddRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'config_id' => 'required|numeric|unique:payment_infos,config_id',//表backend_payment_configs中的id
            'front_name' => 'required|string',//前台名称
            'front_remark' => 'required|string',//前台备注
            'back_name' => 'required|string',//后台名称
            'back_remark' => 'required|string',//后台备注
            'status' => 'required|in:0,1',//状态 1 启用 0 停用
            'sort' => 'required|numeric',//排序
            'rebate_handFee' => 'required|numeric',//返点/手续费
            'max' => 'required|numeric',//最大充值金额
            'min' => 'required|numeric',//最小充值金额
            'payment_vendor_url' => 'required|string',//第三方域名
            'merchant_code' => 'required|string',//商户号
            'merchant_secret' => 'required|string',//商户秘钥
            'public_key' => 'required|string',//第三方公钥
            'private_key' => 'required|string',//第三方私钥
            'app_id' => 'required|string',//第三方终端号
            'platform' => 'required|in:0,1,2',//显示的地方 0 全部 1 电脑端 2 手机端
        ];
    }
}
