<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUserfundSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_daysalaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->tinyInteger('is_tester')->nullable();
            $table->string('username',20)->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent',20)->nullable();
            $table->string('forefathers',1024)->nullable();
            $table->string('parent_str',100)->nullable();
            $table->date('date')->nullable();
            $table->decimal('daysalary',11,2)->nullable()->comment('日工资数额');
            $table->tinyInteger('status')->nullable()->default(0)->comment('发放状态');
            $table->dateTime('sent_time')->nullable()->comment('发放时间');
            $table->decimal('turnover',20,6)->nullable()->default(0.000000)->comment('投注流水');
            $table->decimal('daysalary_percentage',4,1)->nullable()->default(0.0)->comment('日工资比例');
            $table->decimal('bet_commission',20,6)->nullable()->default(0.000000)->comment('投注返点');
            $table->decimal('commission',20,6)->nullable()->default(0.000000)->comment('佣金');
            $table->decimal('team_bet_commission',20,6)->nullable()->default(0.000000)->comment('团队投注返点');
            $table->decimal('team_commission',20,6)->nullable()->default(0.000000)->comment('团队佣金');
            $table->decimal('team_turnover',20,6)->nullable()->default(0.000000)->comment('团队流水');
            $table->unique(['user_id', 'date'],'user_daysalary_user_id_date');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('users_salary_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign',255)->comment('(frontend_users表sign)');
            $table->integer('top_id')->comment('(frontend_users表top_id)');
            $table->integer('parent_id')->comment('(frontend_users表parent_id)');
            $table->integer('user_id')->comment('用户id (frontend_users表id)');
            $table->string('parent_username',64)->comment('父级用户名 (frontend_users表id)');
            $table->string('username',64)->comment('用户名 (frontend_users表username)');
            $table->tinyInteger('user_type')->default(1);
            $table->text('contract');
            $table->tinyInteger('status')->default(1);
            $table->index(['sign', 'user_id'],'user_salary_config_sign_user_id_index');
            $table->index(['top_id', 'parent_id', 'user_id'],'user_salary_config_top_id_parent_id_user_id_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('users_salary_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign',255);
            $table->integer('top_id');
            $table->integer('parent_id');
            $table->integer('user_id');
            $table->string('parent_username',32);
            $table->string('username',32);
            $table->unsignedInteger('amount')->default(0);
            $table->unsignedInteger('real_amount')->default(0);
            $table->unsignedInteger('bets')->default(0);
            $table->unsignedInteger('lose')->default(0);
            $table->decimal('ratio',5,1)->default(0.0);
            $table->integer('day');
            $table->tinyInteger('status')->default(0);
            $table->integer('add_time')->default(0);
            $table->integer('send_time')->default(0);
            $table->integer('resend_time')->default(0);
            $table->index(['sign', 'user_id'],'user_salary_report_sign_user_id_index');
            $table->index(['top_id', 'user_id', 'day', 'add_time'],'user_salary_report_top_id_user_id_day_add_time_index');
            $table->index('day','user_salary_report_day_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
