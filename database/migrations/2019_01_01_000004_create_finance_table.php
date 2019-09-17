<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_withdraw_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            $table->integer('top_id');
            $table->integer('parent_id');
            $table->integer('user_id');
            $table->string('username', 64);
            $table->string('nickname', 64);

            $table->string('order_id', 64)->default('');                // 订单号 和财务那边唯一
            $table->integer('card_id');                                                // 提现卡id 关联到 user_bank_cards的id
            $table->string('bank_sign', 32)->default(0);                // 银行的sign
            $table->unsignedBigInteger('amount')->default(0);                   // 体现金额
            $table->unsignedBigInteger('real_amount')->default(0);              // 实际提现金额

            $table->integer('request_time')->default(0);                        // 请求时间
            $table->integer('check_time')->default(0);                          // 审核时间
            $table->integer('process_time')->default(0);                        // 处理时间
            $table->integer('process_day')->default(0);                         // 处理 日期
            $table->integer('stat_time')->default(0);                           // 统计时间

            $table->string('source', 32)->default('web');               // 来源默认 web

            $table->string('client_ip',20)->default('');                // 客户端IP
            $table->string('description',255)->default('');             // 描述
            // 回调错误返回
            $table->text('desc');

            $table->tinyInteger('status')->default(0);                          // 0 待审核 1 领取　2 审核完成 -2 审核失败;

            $table->integer('check_admin_id')->default(0);                      // 审核管理员ID
            $table->integer('hand_admin_id')->default(0);                       // 手动处理管理员ID

            $table->timestamps();

            $table->index(['partner_sign', 'top_id']);
            $table->index(['partner_sign', 'order_id']);
            $table->index(['partner_sign', 'user_id', 'request_time']);
            $table->index(['partner_sign', 'user_id', 'process_time']);
            $table->index(['partner_sign', 'user_id', 'status']);
        });

        Schema::create('users_withdraw_log', function (Blueprint $table) {
            $table->increments('id');

            $table->char('ip', 15)->default('');
            $table->string('order_id', 64)->default('');

            $table->unsignedInteger("amount")->default(0);

            $table->integer('user_id')->default(0);
            $table->integer('top_id')->default(0);
            $table->string('username', 32)->default('');
            $table->string('nickname', 64)->default('');

            $table->text("request_params");
            $table->mediumText("request_back");
            $table->text("content");

            $table->string("request_reason", 128)->default('');
            $table->tinyInteger("request_status")->default(0);

            $table->string("back_reason", 128)->default('');
            $table->tinyInteger("back_status")->default(0);

            $table->timestamps();

            $table->index(['top_id']);
            $table->index(['order_id', 'top_id']);
        });

        // 充值
        Schema::create('user_recharge', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('top_id');
            $table->integer('parent_id');
            $table->integer('user_id');
            $table->string('username', 64);
            $table->string('nickname', 64)->default('');

            $table->string('order_id', 64)->default('');        // 订单号
            $table->string('channel', 64)->default('');         // 渠道名称
            $table->string('bank_sign', 32)->default('');       // 银行表示会
            $table->unsignedBigInteger('amount')->default(0);           // 充值金额
            $table->unsignedBigInteger('real_amount')->default(0);      // 真实到账

            $table->string('sign', 32)->default('');            // 附言

            $table->char('client_ip', 15)->default('');         // 充值IP
            $table->string('source', 32)->default('web');       // 来源默认 web
            $table->tinyInteger('status')->default(0);                  // 处理状态  0:未处理(默认); -1 请求失败; 2 回调成功 -2 回调失败
            $table->string('fail_reason', 256)->default('');    // 失败原因

            // 时间
            $table->integer('init_time')->default(0);                   // 初始化时间
            $table->integer('request_time')->default(0);                // 请求时间
            $table->integer('callback_time')->default(0);               // 处理时间
            $table->integer('stat_time')->default(0);                   // 统计时间

            $table->string('desc', 256)->default('');           // 充值描述
            $table->integer('admin_id')->default(0);                    // 管理员ID 手工处理者 的ID

            $table->timestamps();

            $table->index(['top_id']);
            $table->index(['order_id', 'top_id']);
            $table->index(['user_id', 'request_time', 'top_id']);
            $table->index(['user_id', 'callback_time', 'top_id']);
        });

        // 充值日志
        Schema::create('user_recharge_log', function (Blueprint $table) {
            $table->increments('id');

            $table->char('ip', 15)->default('');
            $table->string('order_id', 64)->default('');

            $table->unsignedInteger("amount")->default(0);

            $table->integer('user_id')->default(0);
            $table->integer('top_id')->default(0);
            $table->string('username', 32)->default('');
            $table->string('nickname', 64)->default('');

            $table->text("request_params");
            $table->mediumText("request_back");
            $table->text("content");

            $table->string("request_reason", 128)->default('');
            $table->integer("request_time")->default(0);
            $table->tinyInteger("request_status")->default(0);

            $table->char("back_ip", 15)->default('');
            $table->string("back_reason", 128)->default('');
            $table->integer("back_time")->default(0);
            $table->tinyInteger("back_status")->default(0);

            $table->timestamps();
            $table->index(['top_id']);
            $table->index(['user_id', 'top_id']);
            $table->index(['order_id', 'top_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_withdraw_list');
        Schema::dropIfExists('users_withdraw_log');
        Schema::dropIfExists('user_recharge_list');
        Schema::dropIfExists('user_recharge_log');
    }
}
