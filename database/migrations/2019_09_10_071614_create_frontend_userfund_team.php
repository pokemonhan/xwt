<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUserfundTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('is_tester')->nullable();
            $table->string('username',16);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedDecimal('team_deposit',16,4)->nullable()->default(0.0000)->comment('充值总额');
            $table->unsignedDecimal('team_withdrawal',16,4)->nullable()->default(0.0000)->comment('提现总额');
            $table->unsignedDecimal('team_turnover',16,4)->nullable()->default(0.0000)->comment('投注总额');
            $table->unsignedDecimal('team_prize',18,6)->nullable()->default(0.000000)->comment('派奖总额');
            $table->decimal('team_profit',18,6)->nullable()->default(0.000000)->comment('游戏盈亏');
            $table->unsignedDecimal('team_commission',16,6)->nullable()->default(0.000000)->comment('下级返点');
            $table->unsignedDecimal('team_bet_commission',16,6)->nullable()->default(0.000000)->comment('投注返点');
            $table->unsignedDecimal('team_dividend',16,4)->nullable()->default(0.0000)->comment('促销红利');
            $table->decimal('team_daily_salary',16,4)->nullable()->default(0.0000)->comment('日工资');
            $table->decimal('deposit',16,4)->nullable()->default(0.0000);
            $table->decimal('withdrawal',16,4)->nullable()->default(0.0000);
            $table->decimal('turnover',16,4)->nullable()->default(0.0000);
            $table->decimal('prize',16,4)->nullable()->default(0.0000);
            $table->decimal('profit',16,4)->nullable()->default(0.0000);
            $table->decimal('commission',16,4)->nullable()->default(0.0000);
            $table->decimal('bet_commission',16,4)->nullable()->default(0.0000);
            $table->decimal('dividend',16,4)->nullable()->default(0.0000);
            $table->decimal('daily_salary',16,4)->nullable()->default(0.0000);
            $table->unique(['user_id', 'date'],'userid_date');
            $table->unique(['username', 'date'],'username_date');
            $table->index('date','date');
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
