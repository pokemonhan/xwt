<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_lottery_fnf_betable_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lotteries_id',16)->nullable()->comment('彩种标识');
            $table->integer('method_id')->nullable()->comment('玩法id (frontend_lottery_fnf_betable_methods表id)');
            $table->integer('sort')->nullable()->comment('排序');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_lottery_fnf_betable_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_id',32)->comment('彩种系列');
            $table->string('lottery_name',32)->comment('彩种中文名称');
            $table->string('lottery_id',32)->comment('彩种标识');
            $table->string('method_id',32)->comment('玩法标识');
            $table->string('method_name',32)->comment('玩法中文名称');
            $table->string('method_group',32)->comment('玩法组标识');
            $table->string('method_row',32)->nullable()->comment('玩法行');
            $table->integer('group_sort')->default(0);
            $table->integer('row_sort')->default(0);
            $table->integer('method_sort')->default(0);
            $table->tinyInteger('show')->default(1)->comment('是否展示 0否 1是');
            $table->tinyInteger('status')->default(0)->comment('状态 0关闭 1开启');
            $table->integer('total')->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_lottery_notice_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lotteries_id',16)->nullable()->comment('彩种标识');
            $table->string('cn_name',32)->nullable()->comment('彩种中文名');
            $table->tinyInteger('status')->default(0)->nullable()->comment('开启状态：0关闭 1开启');
            $table->integer('sort')->nullable()->comment('排序');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });

        Schema::create('frontend_lottery_redirect_bet_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lotteries_id')->nullable()->comment('彩票id （lottery_lists表id）');
            $table->string('lotteries_sign',16)->nullable()->comment('彩种标识');
            $table->string('pic_path',128)->nullable()->comment('图片');
            $table->unsignedInteger('sort')->nullable()->comment('排序');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });

        Schema::create('frontend_popular_chess_cards_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chess_card_id')->nullable();
            $table->string('name',45)->nullable();
            $table->string('icon',128)->nullable();
            $table->integer('sort')->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_popular_e_game_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('computer_game_id')->nullable();
            $table->string('name',45)->nullable();
            $table->string('icon',128)->nullable();
            $table->integer('sort')->nullable();
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
