<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('data_source_id', 36)->nullable()->comment('資料來源');
            $table->foreignUuid('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade')
                ->comment('所屬群組');
            $table->foreignUuid('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade')
                ->comment('所屬角色');
            $table->string('organization_id', 36)->nullable()->comment('所屬組織');
            $table->string('user_type_id', 36)->nullable()->comment('所屬用戶類別');
            $table->string('avatar', 120)->nullable()->comment('頭像圖片');
            $table->string('account', 120)->unique()->comment('帳號');
            $table->string('password', 120)->comment('密碼');
            $table->string('telecom_number', 30)->nullable()->comment('電信號碼');
            $table->string('name', 100)->nullable()->comment('使用者名稱');
            $table->tinyInteger('status')->default(2)->nullable()->comment('狀態(值1:啟用, 值2:停用)');
            $table->string('parent_id', 36)->nullable()->comment('所屬主帳戶');
            $table->string('create_user_id', 36)->nullable()->comment('建立人員');
            $table->string('update_user_id', 36)->nullable()->comment('修改人員');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
