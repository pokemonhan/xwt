<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUserfund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_user_dividend_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign',255)->comment('sign （frontend_users表sign）');
            $table->integer('top_id')->comment('上级id （frontend_users表top_id）');
            $table->integer('parent_id')->comment('（frontend_users表parent_id）');
            $table->integer('user_id')->comment('用户id （frontend_users表id）');
            $table->string('username',20)->comment('用户名 （frontend_users表username）');
            $table->text('contract')->nullable();
            $table->text('temp')->nullable();
            $table->tinyInteger('verify')->default(0);
            $table->tinyInteger('status')->default(0)->comment('状态 0关闭 1开启');
            $table->integer('verify_time')->default(0);
            $table->index('sign','user_dividend_config_sign_index');
            $table->index(['parent_id', 'user_id'],'user_dividend_config_parent_id_user_id_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_user_dividend_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign',255);
            $table->integer('top_id');
            $table->integer('from_id');
            $table->integer('user_id');
            $table->text('contract')->nullable();
            $table->integer('did')->default(0);
            $table->tinyInteger('instead')->default(0);
            $table->string('flag',20)->default('');
            $table->dateTime('from_time');
            $table->dateTime('to_time');
            $table->string('username',32);
            $table->decimal('amount',15,4)->default(0.0000);
            $table->decimal('real_amount',15,4)->default(0.0000);
            $table->decimal('bets',15,4)->default(0.0000);
            $table->decimal('bonus',15,4)->default(0.0000);
            $table->decimal('points',15,4)->default(0.0000);
            $table->decimal('brokerage',15,4)->default(0.0000);
            $table->decimal('gift',15,4)->default(0.0000);
            $table->decimal('salary',15,4)->default(0.0000);
            $table->decimal('profit',15,4)->default(0.0000);
            $table->integer('rate')->default(0);
            $table->integer('process_time')->default(0);
            $table->integer('verify_time')->default(0);
            $table->tinyInteger('speed')->default(0);
            $table->index('sign','user_dividend_report_sign_index');
            $table->index(['user_id', 'verify_time'],'user_dividend_report_user_id_verify_time_index');
            $table->index(['from_id', 'verify_time'],'user_dividend_report_from_id_verify_time_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_users_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id （frontend_users表id）');
            $table->unsignedDecimal('balance',18,4)->default(0.0000)->comment('资金');
            $table->unsignedDecimal('frozen',18,4)->default(0.0000)->comment('冻结资金');
            $table->tinyInteger('status')->default(0)->comment('状态 0关闭 1开启');
            $table->index(['user_id', 'balance'],'user_accounts_user_id_balance_index');
            $table->index(['user_id', 'frozen'],'user_accounts_user_id_frozen_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_users_accounts_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number',64)->nullable();
            $table->string('sign',32);
            $table->integer('user_id')->comment('用户id（frontend_users表id）');
            $table->integer('top_id')->nullable()->comment('（frontend_users表top_id）');
            $table->integer('parent_id')->nullable()->comment('（frontend_users表parent_id）');
            $table->string('rid',256)->comment('（frontend_users表rid）');
            $table->string('username',32)->comment('（frontend_users表rid）');
            $table->integer('from_id')->default(0);
            $table->integer('from_admin_id')->default(0);
            $table->integer('to_id')->default(0);
            $table->string('type_sign',32)->comment('帐变类型sign（account_change_types表sign）');
            $table->string('type_name',32)->nullable()->comment('帐变类型name（account_change_types表name）');
            $table->tinyInteger('in_out')->nullable();
            $table->string('lottery_id',32)->nullable()->comment('彩票（lottery_lists表en_name）');
            $table->string('method_id',32)->nullable()->comment('彩票玩法（lottery_methods表method_id）');
            $table->integer('project_id')->default(0);
            $table->string('issue',64)->nullable()->comment('彩票期号（lottery_issues表issue）');
            $table->string('activity_sign',32)->nullable();
            $table->unsignedDecimal('amount',18,4)->default(0.0000)->comment('变动前的资金');
            $table->unsignedDecimal('before_balance',18,4)->default(0.0000)->comment('变动资金');
            $table->unsignedDecimal('balance',18,4)->default(0.0000)->comment('变动后的资金');
            $table->unsignedDecimal('before_frozen_balance',18,4)->default(0.0000);
            $table->unsignedDecimal('frozen_balance',18,4)->default(0.0000);
            $table->tinyInteger('frozen_type')->default(0);
            $table->tinyInteger('is_tester')->default(0)->comment('是否是测试用户（frontend_users表is_tester）');
            $table->string('param',64)->nullable()->comment('账变类型id');
            $table->integer('process_time')->default(0);
            $table->string('desc',256);
            $table->index(['sign' , 'user_id', 'process_time'], 'account_change_report_sign_user_id_process_time_index');
            $table->index(['sign', 'type_sign', 'process_time'], 'account_change_report_sign_type_sign_process_time_index');
            $table->index(['sign', 'lottery_id', 'method_id'], 'account_change_report_sign_lottery_id_method_id_index');
            $table->index(['sign', 'issue', 'project_id'], 'account_change_report_sign_issue_project_id_index');
            $table->index(['sign', 'project_id'], 'account_change_report_sign_project_id_day_index');
            $table->index(['sign', 'process_time'], 'account_change_report_sign_process_time_index');
            $table->index(['user_id', 'type_sign', 'process_time'], 'account_change_report_user_id_type_sign_process_time_index');
            $table->index('user_id', 'account_change_report_user_id_index');
            $table->index('rid', 'account_change_report_rid_index');
            $table->index('activity_sign', 'account_change_report_activity_sign_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_users_accounts_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',32)->comment('类型名称');
            $table->string('sign',32)->comment('标识');
            $table->tinyInteger('in_out')->default(1)->comment('出入类型 1增加 2减少');
            $table->string('param',45)->nullable()->comment('需要的字段');
            $table->tinyInteger('frozen_type')->default(1);
            $table->tinyInteger('activity_sign')->default(1);
            $table->integer('admin_id')->default(0);
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_users_accounts_types_params', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label',32)->nullable();
            $table->string('param',32)->nullable();
            $table->unsignedTinyInteger('compatible')->nullable()->default(1)->comment('1兼容两张表都存在数据');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_users_bank_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id （frontend_users表id）');
            $table->integer('parent_id')->comment('（frontend_users表parent_id）');
            $table->integer('top_id')->comment('（frontend_users表top_id）');
            $table->string('rid',128)->comment('（frontend_users表rid）');
            $table->string('username',64)->comment('用户名 （frontend_users表username）');
            $table->string('bank_sign',32)->comment('银行code');
            $table->string('bank_name',64)->comment('银行');
            $table->string('owner_name',128)->comment('真实姓名');
            $table->string('card_number',64)->comment('银行卡号');
            $table->string('province_id',64)->comment('省份');
            $table->string('city_id',64)->comment('市');
            $table->string('branch',64);
            $table->tinyInteger('status')->default(0)->comment('状态 0不可以 1可用');
            $table->index('user_id','user_bank_cards_user_id_index');
            $table->index('owner_name','user_bank_cards_owner_name_index');
            $table->index('card_number','user_bank_cards_card_number_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_users_accounts_reports_params_with_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->string('casino_game_code',255)->nullable();
            $table->unsignedDecimal('amount',18,4)->default(0.0000)->comment('变动前的资金');
            $table->integer('user_id')->comment('用户id（frontend_users表id）');
            $table->integer('project_id')->default(0);
            $table->integer('from_id')->default(0);
            $table->integer('from_admin_id')->default(0);
            $table->integer('to_id')->default(0);
            $table->string('lottery_id',32)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->comment('彩票（lottery_lists表en_name）');
            $table->string('method_id',32)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment('彩票玩法（lottery_methods表method_id）');
            $table->string('issue',64)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment('彩票期号（lottery_issues表issue）');
            $table->string('casino_game_category',64)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('casino_game_plat',64)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
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
        Schema::dropIfExists('frontend_user_dividend_configs');
        Schema::dropIfExists('frontend_user_dividend_reports');
        Schema::dropIfExists('frontend_users_accounts');
        Schema::dropIfExists('frontend_users_accounts_reports');
        Schema::dropIfExists('frontend_users_accounts_types');
        Schema::dropIfExists('frontend_users_accounts_types_params');
        Schema::dropIfExists('frontend_users_bank_cards');
        Schema::dropIfExists('frontend_users_accounts_reports_params_with_values');
    }
}
