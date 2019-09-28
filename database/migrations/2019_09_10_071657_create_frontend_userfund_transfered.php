<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUserfundTransfered extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transfer_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_parent_id')->comment('发送人parent_id（frontend_users表parent_id）');
            $table->integer('from_user_id')->comment('发送人id（frontend_users表id）');
            $table->string('from_username',64)->comment('发送人名称（frontend_users表username）');
            $table->integer('to_parent_id')->comment('接收人parent_id（frontend_users表parent_id）');
            $table->integer('to_user_id')->comment('接收人id（frontend_users表id）');
            $table->string('to_username',64)->comment('接收名称（frontend_users表username）');
            $table->unsignedInteger('amount')->comment('金额');
            $table->integer('add_time')->comment('时间');
            $table->integer('day')->comment('日期');
            $table->index(['from_user_id', 'add_time'],'user_transfer_records_from_user_id_add_time_index');
            $table->index(['to_user_id', 'add_time'],'user_transfer_records_to_user_id_add_time_index');
            $table->index(['from_user_id', 'to_user_id', 'add_time'],'user_transfer_records_from_user_id_to_user_id_add_time_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_users_transfered_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign',32);
            $table->integer('top_id');
            $table->integer('parent_id');
            $table->integer('user_id');
            $table->string('username',64);
            $table->string('rid',256);
            $table->tinyInteger('mode')->default(1);
            $table->tinyInteger('type')->default(1);
            $table->unsignedBigInteger('amount');
            $table->integer('admin_id')->default(0);
            $table->string('admin_name',32)->default('0');
            $table->string('reason',128)->default('');
            $table->string('process_admin_name',32)->default('0');
            $table->string('process_reason',128)->default('');
            $table->integer('add_time');
            $table->integer('process_time')->default(0);
            $table->integer('stat_time')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->index('user_id','user_admin_transfer_records_user_id_index');
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
        Schema::dropIfExists('user_transfer_records');
        Schema::dropIfExists('frontend_users_transfered_records');
    }
}
