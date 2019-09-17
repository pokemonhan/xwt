<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonGameLottery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_basic_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('lottery_type');
            $table->unsignedTinyInteger('series_id')->nullable();
            $table->string('series_code',20)->nullable();
            $table->unsignedInteger('type')->nullable();
            $table->string('name',20);
            $table->string('wn_function',64)->nullable();
            $table->tinyInteger('sequencing')->comment('定位');
            $table->unsignedTinyInteger('digital_count');
            $table->tinyInteger('unique_count')->nullable()->comment('去重后的数字个数');
            $table->tinyInteger('max_repeat_time')->nullable()->comment('重号的最大重复次数');
            $table->tinyInteger('min_repeat_time')->nullable();
            $table->unsignedTinyInteger('span')->nullable();
            $table->unsignedTinyInteger('min_span')->nullable();
            $table->unsignedTinyInteger('choose_count')->nullable()->comment('计算组合时需要选择的数字个数');
            $table->unsignedTinyInteger('special_count')->nullable();
            $table->tinyInteger('fixed_number')->nullable()->comment('固定号码');
            $table->unsignedSmallInteger('price')->default(2);
            $table->unsignedTinyInteger('buy_length')->default(3);
            $table->unsignedTinyInteger('wn_length')->default(3);
            $table->unsignedTinyInteger('wn_count');
            $table->string('valid_nums',50)->default('');
            $table->string('rule',50)->default('');
            $table->unsignedInteger('all_count')->default(0);
            $table->string('bet_rule',1024)->nullable();
            $table->string('bonus_note',1024)->nullable();
            $table->index('type','type');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_basic_ways', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('lottery_type')->default(1);
            $table->string('name',10);
            $table->string('function',64)->charset('latin1')->collation('latin1_swedish_ci');
            $table->string('description',255)->nullable();
            $table->unsignedInteger('sequence')->nullable();
            $table->unique(['lottery_type', 'name'],'type_name');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_issue_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lottery_id',255)->comment('彩票id （lottery_lists表id）');
            $table->string('lottery_name',255)->comment('彩票名（lottery_lists表cn_name）');
            $table->time('begin_time')->comment('开始时间');
            $table->time('end_time')->comment('结束时间');
            $table->integer('issue_seconds')->comment('奖期间隔时间（秒）');
            $table->time('first_time')->comment('第一期时间');
            $table->smallInteger('adjust_time');
            $table->smallInteger('encode_time');
            $table->smallInteger('issue_count');
            $table->tinyInteger('status')->default(1)->comment('状态 0关闭 1开启');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('lottery_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lottery_id',32)->comment('彩种标识');
            $table->string('lottery_name',32)->comment('彩种中文名');
            $table->string('issue',64)->nullable()->comment('奖期号');
            $table->integer('issue_rule_id')->comment('奖期规则id （lottery_issue_rules）');
            $table->integer('begin_time')->comment('奖期开始时间');
            $table->integer('end_time')->comment('奖期结束时间');
            $table->integer('official_open_time')->comment('官方开奖时间');
            $table->integer('allow_encode_time')->default(0)->comment('录号时间');
            $table->string('official_code',64)->nullable()->comment('开奖号码');
            $table->unsignedTinyInteger('status_encode')->default(0)->comment('录号状态 （0未录号 1已录号）');
            $table->unsignedTinyInteger('status_calculated')->default(0);
            $table->unsignedTinyInteger('status_prize')->default(0);
            $table->unsignedTinyInteger('status_commission')->default(0);
            $table->unsignedTinyInteger('status_trace')->default(0);
            $table->integer('encode_time')->default(0);
            $table->integer('calculated_time')->default(0);
            $table->integer('prize_time')->default(0);
            $table->integer('commission_time')->default(0);
            $table->integer('trace_time')->default(0);
            $table->integer('encode_id')->nullable();
            $table->string('encode_name',64)->nullable();
            $table->integer('day')->default(0);
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('lottery_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lottery_type')->nullable();
            $table->string('cn_name',32)->comment('彩票中文名');
            $table->string('en_name',32)->comment('彩票英文名');
            $table->string('series_id',32)->comment('彩票系列 （lottery_series表id）');
            $table->tinyInteger('is_fast')->default(1)->comment('是否是快彩');
            $table->tinyInteger('auto_open')->default(0);
            $table->integer('max_trace_number')->default(50);
            $table->integer('day_issue')->comment('一天的期数');
            $table->string('issue_format',32);
            $table->string('issue_type',32)->default('day');
            $table->string('valid_code',256)->comment('合法号码');
            $table->integer('code_length')->comment('号码长度');
            $table->string('positions',256)->comment('号码位置');
            $table->integer('min_prize_group')->comment('最小奖金组');
            $table->integer('max_prize_group')->comment('最大奖金组');
            $table->integer('min_times');
            $table->integer('max_times');
            $table->decimal('max_profit_bonus',15,4)->nullable();
            $table->string('valid_modes',32);
            $table->tinyInteger('status')->default(0)->comment('状态 0关闭 1开启');
            $table->string('icon_path',128)->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('lottery_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_id',32)->collation('utf8mb4_unicode_ci')->comment('系列标识');
            $table->string('lottery_name',32)->collation('utf8mb4_unicode_ci')->comment('彩种中文名');
            $table->string('lottery_id',32)->collation('utf8mb4_unicode_ci')->comment('彩种标识');
            $table->string('method_id',32)->collation('utf8mb4_unicode_ci')->comment('玩法标识');
            $table->string('method_name',32)->collation('utf8mb4_unicode_ci')->comment('玩法中文名');
            $table->string('method_group',32)->collation('utf8mb4_unicode_ci')->comment('玩法组');
            $table->string('method_row',32)->collation('utf8mb4_unicode_ci')->nullable()->comment('玩法行');
            $table->integer('group_sort')->default(0);
            $table->integer('row_sort')->default(0);
            $table->integer('method_sort')->default(0);
            $table->tinyInteger('show')->default(1)->comment('展示状态 （0不显示 1显示）');
            $table->tinyInteger('status')->default(0)->comment('开启状态 （0关闭 1开启）');
            $table->integer('total')->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_methods_layout_displays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_code',32);
            $table->string('display_name',32);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_methods_layouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('validation_id');
            $table->string('display_code',32);
            $table->unsignedInteger('rule_id')->comment('lottery_methods_number_rules表id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_methods_number_button_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',32);
            $table->json('value');
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_methods_standards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_id',32)->comment('系列标识');
            $table->string('lottery_name',32)->comment('彩种中文名');
            $table->string('lottery_id',32)->comment('彩种标识');
            $table->string('method_id',32)->comment('玩法标识');
            $table->string('method_name',32)->comment('玩法中文名');
            $table->string('method_group',32)->comment('玩法组');
            $table->string('method_row',32)->nullable()->comment('玩法行');
            $table->integer('group_sort')->default(0);
            $table->integer('row_sort')->default(0);
            $table->integer('method_sort')->default(0);
            $table->tinyInteger('show')->default(1)->comment('展示状态 （0不显示 1显示）');
            $table->tinyInteger('status')->default(0)->comment('开启状态 （0关闭 1开启）');
            $table->integer('total')->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_methods_validations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method_id',16)->nullable()->comment('玩法标识');
            $table->string('regex',64)->nullable()->comment('正则表达式');
            $table->integer('total')->nullable();
            $table->integer('min_block')->nullable();
            $table->integer('max_block')->nullable();
            $table->text('sample_min')->nullable();
            $table->text('sample_max')->nullable();
            $table->string('explode',45)->nullable();
            $table->tinyInteger('num_count')->nullable();
            $table->string('spliter',16)->nullable();
            $table->tinyInteger('type')->nullable()->comment('1.数字   2.字母   3.数字+字母  4.range字段自定义');
            $table->string('range',64)->nullable();
            $table->string('example',255)->nullable();
            $table->string('describe',255)->nullable();
            $table->string('helper',255)->nullable();
            $table->string('button_id',32)->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_methods_ways_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method_id',16)->nullable()->comment('玩法标识');
            $table->tinyInteger('level')->nullable()->comment('等级');
            $table->integer('basic_method_id')->nullable()->comment('lottery_basic_methods表id');
            $table->string('method_name',16)->nullable()->comment('玩法中文名');
            $table->string('level_name',32)->nullable();
            $table->string('series_id',16)->nullable()->comment('系列标识');
            $table->string('position',45)->nullable()->comment('开奖位置');
            $table->integer('count')->nullable();
            $table->decimal('prize',10,2)->nullable()->comment('奖金');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_name',45)->nullable();
            $table->string('title',45)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('price_difference')->nullable()->default(0);
            $table->string('encode_splitter',15)->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_series_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('series_id_old');
            $table->string('series_code',255)->nullable()->comment('系列标识');
            $table->string('name',30)->nullable()->comment('玩法中文名');
            $table->unsignedMediumInteger('basic_method_id')->nullable()->comment('lottery_basic_methods表id');
            $table->tinyInteger('offset')->default(0);
            $table->tinyInteger('hidden')->default(0);
            $table->tinyInteger('open')->default(1);
            $table->index('series_id_old','series_id');
            $table->index('hidden','hidden');
            $table->index('open','open');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_series_ways', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('lottery_type')->nullable()->comment('类型（1.开奖号码可重复 2.开奖号码不可重复）');
            $table->unsignedTinyInteger('series_id_old');
            $table->integer('series_id')->nullable()->comment('彩种系列id');
            $table->string('series_code',20)->nullable()->comment('彩种系列标识');
            $table->string('lottery_method_id',10)->nullable()->comment('玩法标识');
            $table->string('name',30)->comment('玩法中文名');
            $table->string('short_name',30)->nullable()->default('');
            $table->unsignedInteger('series_way_method_id')->nullable();
            $table->unsignedTinyInteger('basic_way_id');
            $table->string('basic_methods',200);
            $table->string('series_methods',200);
            $table->unsignedTinyInteger('digital_count')->nullable();
            $table->unsignedSmallInteger('price');
            $table->string('offset',30)->nullable();
            $table->unsignedTinyInteger('buy_length')->nullable();
            $table->unsignedTinyInteger('wn_length')->nullable();
            $table->unsignedTinyInteger('wn_count')->nullable();
            $table->tinyInteger('area_count')->nullable();
            $table->string('area_config',20)->nullable();
            $table->string('area_position',10)->nullable();
            $table->string('valid_nums',50)->nullable();
            $table->string('rule',50)->nullable();
            $table->string('all_count',100);
            $table->string('bonus_note',1024)->nullable();
            $table->string('bet_note',1024)->nullable();
            $table->tinyInteger('is_enable_extra')->default(0);
            $table->unique(['series_way_method_id', 'basic_way_id', 'series_methods', 'offset', 'area_position'],'way_method');
            $table->index('series_id_old','series_id');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_prize_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('series_id_old');
            $table->string('series_code',255)->nullable();
            $table->unsignedInteger('group_id');
            $table->string('group_name',20);
            $table->unsignedMediumInteger('classic_prize');
            $table->unsignedInteger('method_id');
            $table->string('method_name',20)->nullable();
            $table->unsignedTinyInteger('level');
            $table->unsignedDecimal('probability',11,11);
            $table->decimal('old_prize',12,4)->nullable();
            $table->decimal('prize',12,4)->nullable();
            $table->unsignedDecimal('full_prize',12,4);
            $table->index('group_id','group_id');
            $table->index('series_id_old','series_id');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_prize_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('series_id_old');
            $table->string('series_code',255)->nullable();
            $table->unsignedTinyInteger('type');
            $table->string('name',20);
            $table->unsignedMediumInteger('classic_prize');
            $table->unsignedDecimal('water',4,4);
            $table->unique(['series_code', 'name'],'series_name');
            $table->index('series_id_old','series_id');
            $table->index('type','type');
            $table->index('classic_prize','idx_classic_prize');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('lottery_prize_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('lottery_type_id');
            $table->unsignedMediumInteger('basic_method_id');
            $table->unsignedTinyInteger('level');
            $table->decimal('probability',11,11);
            $table->unsignedDecimal('full_prize',14,4)->nullable();
            $table->unsignedDecimal('fixed_prize',14,4)->nullable();
            $table->unsignedDecimal('max_prize',10,2);
            $table->unsignedMediumInteger('max_group');
            $table->unsignedDecimal('min_water',4,4)->default(0.0200);
            $table->string('rule',50)->collation('utf8_general_ci');
            $table->unsignedInteger('prize_allcount')->default(0);
            $table->unique(['basic_method_id', 'level'],'method_level');
            $table->index('lottery_type_id','lottery_type_id');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
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
