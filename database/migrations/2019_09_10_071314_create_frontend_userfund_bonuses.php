<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUserfundBonuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->date('period')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('username',255)->nullable();
            $table->integer('parent_user_id')->nullable();
            $table->tinyInteger('is_tester')->nullable();
            $table->decimal('salary_total',20,6)->nullable()->comment('日工资总计');
            $table->decimal('dividend_total',20,6)->nullable()->comment('促销红利总计');
            $table->decimal('commission_total',20,6)->nullable()->comment('佣金总计');
            $table->decimal('prize_total',20,6)->nullable()->comment('派奖总计');
            $table->decimal('turnover_total',20,6)->nullable()->comment('投注流水总计');
            $table->integer('bet_counts')->nullable()->comment('下级有效投注人数');
            $table->tinyInteger('bonus_percentage')->nullable()->comment('分红比例');
            $table->decimal('net_profit_total',20,6)->nullable()->comment('净盈亏');
            $table->decimal('bonus_total',20,6)->nullable()->default(0.000000)->comment('分红总计');
            $table->unique(['period', 'user_id'],'user_bet_counts_id_uindex');
            $table->nullableTimestamps();
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
