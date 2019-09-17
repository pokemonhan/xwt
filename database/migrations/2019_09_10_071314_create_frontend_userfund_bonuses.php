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
            $table->decimal('salary_total',20,6)->nullable();
            $table->decimal('dividend_total',20,6)->nullable();
            $table->decimal('commission_total',20,6)->nullable();
            $table->decimal('prize_total',20,6)->nullable();
            $table->decimal('turnover_total',20,6)->nullable();
            $table->integer('bet_counts')->nullable();
            $table->tinyInteger('bonus_percentage')->nullable();
            $table->decimal('net_profit_total',20,6)->nullable();
            $table->decimal('bonus_total',20,6)->nullable()->default(0.000000);
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
