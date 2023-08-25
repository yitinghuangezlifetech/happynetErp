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
        Schema::create('staff', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('organization_id', 36)->nullable()->comment('所屬組織');
            $table->string('department_id', 36)->nullable()->comment('所屬部門');
            $table->string('role_id', 36)->nullable()->comment('所屬角色');
            $table->string('staff_code', 20)->nullable()->comment('員工代碼');
            $table->string('name', 60)->nullable()->comment('員工姓名');
            $table->string('bonus_percent', 20)->nullable()->comment('員工奬金');
            $table->string('email', 60)->nullable()->comment('員工email（登入帳號用）');
            $table->string('password')->nullable()->comment('員工密碼');
            $table->tinyInteger('status')->nullable()->default(1)->comment('狀態（值1:生效，值2:失效）');
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
        Schema::dropIfExists('staff');
    }
};
