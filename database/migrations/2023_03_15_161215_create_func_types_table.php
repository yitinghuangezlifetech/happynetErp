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
        Schema::create('func_types', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('parent_id', 36)->nullable()->comment('父層ID');
            $table->string('type_code', 50)->nullable()->comment('類別代碼');
            $table->string('type_value', 10)->nullable()->comment('類別代碼值');
            $table->string('type_name', 50)->nullable()->comment('類別名稱');
            $table->string('description', 250)->nullable()->comment('描述');
            $table->string('status', 2)->nullable()->comment('狀態，可選值為 1 (啟用)或 2(停用)');
            $table->string('dsp', 2)->nullable()->comment('顯示，可選值為 1 (顯示)或 2(隱藏)');
            $table->integer('sort')->nullable()->default(0)->comment('排序(由小排到大)');
            $table->string('create_user_id', 36)->nullable()->comment('建立人員');
            $table->string('update_user_id', 36)->nullable()->comment('修改人員');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_types');
    }
};
