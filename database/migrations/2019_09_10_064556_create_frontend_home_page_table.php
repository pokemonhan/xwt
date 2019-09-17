<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendHomePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_page_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',45)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->string('pic_path',128)->nullable()->comment('原图');
            $table->string('thumbnail_path',128)->nullable()->comment('缩略图');
            $table->tinyInteger('type')->nullable()->comment('1内部 2活动');
            $table->string('redirect_url',128)->nullable()->comment('跳转地址');
            $table->integer('activity_id')->nullable()->comment('活动id （frontend_activity_contents表id）');
            $table->tinyInteger('status')->nullable()->comment('状态 0关闭 1开启');
            $table->timestamp('start_time')->nullable()->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment('结束时间');
            $table->unsignedInteger('sort')->nullable()->comment('排序');
            $table->tinyInteger('flag')->comment('banner 属于哪端,1:网页端banner ,2:手机端banner');
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
