<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUserfundCommissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->string('username',16);
            $table->string('rid',1024);
            $table->unsignedInteger('account_id');
            $table->tinyInteger('is_tester')->nullable();
            $table->string('lottery_sign',16)->default('0');
            $table->string('issue',15)->collation('utf8_general_ci');
            $table->decimal('bet_amount',16,6)->default(0.000000);
            $table->decimal('amount',16,6)->default(0.000000);
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->index('project_id','prj_bonus_sets_prj_id_index');
            $table->index('status','prj_bonus_sets_gived_index');
            $table->index(['lottery_sign', 'issue', 'status'],'lottery_id');
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
