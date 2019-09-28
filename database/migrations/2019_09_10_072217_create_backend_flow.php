<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackendFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backend_admin_audit_flow_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable()->comment('提交审核的管理员id （backend_admin_users表id）');
            $table->integer('auditor_id')->nullable()->comment('审核的管理员id （backend_admin_users表id）');
            $table->text('apply_note')->collation('utf8_general_ci')->nullable()->comment('提交审核的备注');
            $table->text('auditor_note')->collation('utf8_general_ci')->nullable()->comment('审核返回的备注');
            $table->string('admin_name',64)->collation('utf8_general_ci')->nullable()->comment('提交审核的管理员name （backend_admin_users表name）');
            $table->string('auditor_name',64)->collation('utf8_general_ci')->nullable()->comment('审核的管理员name （backend_admin_users表name）');
            $table->string('username',64)->collation('utf8_general_ci')->nullable()->comment('用户名（frontend_users表username）');
            $table->timestamp('created_at')->nullable()->comment('applied_at');
            $table->timestamp('updated_at')->nullable()->comment('audited_at');
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
        Schema::dropIfExists('backend_admin_audit_flow_lists');
    }
}
