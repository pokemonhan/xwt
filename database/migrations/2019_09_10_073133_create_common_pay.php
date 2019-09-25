<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonPay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_recharge_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->comment('用户id（frontend_users表id）');
            $table->string('user_name',45)->nullable()->comment('用户name（frontend_users表username）');
            $table->tinyInteger('is_tester')->nullable()->comment('是否是测试用户（frontend_users表is_tester）');
            $table->integer('top_agent')->nullable()->comment('用户最上级id（frontend_users表top_id）');
            $table->string('channel',16)->nullable();
            $table->integer('payment_id')->nullable()->comment('支付通道id    (frontend_system_banks表id)');
            $table->string('payment_type_sign',200)->nullable()->comment('支付方式所属的种类');
            $table->decimal('amount',10,3)->nullable()->comment('充值金额');
            $table->string('company_order_num',45)->nullable()->comment('订单号');
            $table->string('third_party_order_num',45)->nullable()->comment('第三方订单号');
            $table->tinyInteger('deposit_mode')->nullable()->comment('1人工充值 0 自动');
            $table->decimal('real_amount',10,3)->nullable()->comment('实际支付金额');
            $table->decimal('fee',10,3)->nullable()->comment('手续费');
            $table->integer('audit_flow_id')->nullable()->comment('审核表id（backend_admin_audit_flow_lists表id）');
            $table->tinyInteger('status')->nullable()->comment('0正在充值 1充值成功 2充值失败 10待审核 11审核通过 12 审核拒绝');
            $table->string('client_ip',64)->nullable();
            $table->string('source',16)->nullable()->comment('来源');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('users_recharge_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_order_num',45)->nullable()->comment('订单号');
            $table->string('log_num',45)->nullable()->comment('（backend_system_logs表log_uuid）');
            $table->decimal('real_amount',10,3)->nullable()->comment('实际支付金额');
            $table->tinyInteger('deposit_mode')->nullable()->comment('1人工充值 0 自动');
            $table->tinyInteger('req_type')->nullable();
            $table->string('req_type_1_params',255)->nullable();
            $table->string('req_type_2_params',255)->nullable();
            $table->string('user_recharge_logcol2',255)->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('backend_admin_recharge_permit_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->nullable()->comment('管理组id (backend_admin_access_groups表id)');
            $table->string('group_name',45)->nullable()->comment('管理组name (backend_admin_access_groups表name)');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_admin_recharge_pocess_amounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable()->comment('管理员id （backend_admin_users表id）');
            $table->decimal('fund',10,2)->nullable()->default(0.00)->comment('人工充值额度');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_admin_rechargehuman_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->nullable()->comment('类型 （0系统操作 1超管对管理员操作 2管理员对用户操作 3超管对用户操作）');
            $table->tinyInteger('in_out')->nullable()->comment('资金类型 （0减少 1增加）');
            $table->integer('super_admin_id')->nullable()->comment('超级管理员id （backend_admin_users表id）');
            $table->string('super_admin_name',45)->nullable()->comment('超级管理员name （backend_admin_users表name）');
            $table->integer('admin_id')->nullable()->comment('管理员id （backend_admin_users表id）');
            $table->string('admin_name',45)->nullable()->comment('管理员name （backend_admin_users表name）');
            $table->integer('user_id')->nullable()->comment('用户id （frontend_users表id）');
            $table->string('user_name',45)->nullable()->comment('用户id （frontend_users表username）');
            $table->decimal('amount',10,2)->nullable()->comment('金额');
            $table->text('comment')->nullable()->comment('内容');
            $table->integer('audit_flow_id')->nullable()->comment('审核流程表id （backend_admin_audit_flow_lists表id）');
            $table->tinyInteger('status')->nullable()->comment('审核状态 （0待审核 1审核通过 2审核驳回）');
            $table->integer('recharge_id')->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_payment_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_vendor_id')->default(0)->comment('支付厂商的id');
            $table->string('payment_vendor_name',200)->default('')->comment('支付方式厂商名称');
            $table->string('payment_vendor_sign',200)->default('')->comment('支付方式厂商标记');
            $table->integer('payment_type_id')->default(0)->comment('支付类型的id');
            $table->string('payment_type_name',200)->default('')->comment('支付方式种类名称');
            $table->string('payment_type_sign',200)->default('')->comment('支付方式种类标记');
            $table->string('banks_code',255)->default('')->comment('网银支付时系统银行码与支付厂商银行码对照表');
            $table->string('payment_name',200)->default('')->comment('支付方式名称');
            $table->string('payment_sign',200)->default('')->comment('支付方式标记');
            $table->tinyInteger('request_mode')->default(0)->comment('支付的请求方式 0 jump 1 json');
            $table->tinyInteger('direction')->default(1)->comment('金流的方向 1 入款 0 出款');
            $table->tinyInteger('status')->default(1)->comment('状态 1 上架 0 下架');
            $table->string('request_url',255)->default('')->comment('支付方式请求地址');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_payment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_type_name',200)->default('')->comment('支付方式种类名称');
            $table->string('payment_type_sign',200)->default('')->comment('支付方式种类标记');
            $table->tinyInteger('is_bank')->default(0)->comment('是否是银行 0 不是 1 是');
            $table->string('payment_ico',255)->default('')->comment('支付方式图标');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_payment_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_vendor_name',200)->default('')->comment('支付方式厂商名称');
            $table->string('payment_vendor_sign',200)->default('')->comment('支付方式厂商标记');
            $table->string('whitelist_ips',255)->default('')->comment('ip白名单');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('payment_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->default(0)->comment('表backend_payment_configs中的id');
            $table->string('front_name',200)->default('')->comment('前台名称');
            $table->string('front_remark',255)->default('')->comment('前台备注');
            $table->string('back_name',200)->default('')->comment('后台名称');
            $table->string('back_remark',255)->default('')->comment('后台备注');
            $table->tinyInteger('direction')->default(1)->comment('金流的方向 1 入款 0 出款');
            $table->tinyInteger('status')->default(1)->comment('状态 1 启用 0 停用');
            $table->string('payment_name',200)->default('')->comment('支付方式名称');
            $table->string('payment_sign',200)->default('')->comment('支付方式标记');
            $table->string('payment_vendor_sign',200)->default('')->comment('支付方式厂商标记');
            $table->string('payment_type_sign',200)->default('')->comment('支付方式种类标记');
            $table->integer('sort')->default(0)->comment('排序');
            $table->decimal('rebate_handFee',20,4)->default(0.0000)->comment('返点/手续费');
            $table->decimal('max',20,4)->default(0.0000)->comment('最大充值金额');
            $table->decimal('min',20,4)->default(0.0000)->comment('最小充值金额');
            $table->string('request_url',255)->default('')->comment('支付方式请求地址');
            $table->string('back_url',255)->default('')->comment('支付方式的返回地址');
            $table->string('payment_vendor_url',255)->default('')->comment('第三方域名');
            $table->string('merchant_code',255)->default('')->comment('商户号');
            $table->text('merchant_secret')->default('')->nullable()->comment('商户秘钥');
            $table->text('public_key')->default('')->nullable()->comment('第三方公钥');
            $table->text('private_key')->default('')->nullable()->comment('第三方私钥');
            $table->string('app_id',255)->default('')->comment('第三方终端号');
            $table->tinyInteger('platform')->default(0)->comment('显示的地方 0 全部 1 电脑端 2 手机端');
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
