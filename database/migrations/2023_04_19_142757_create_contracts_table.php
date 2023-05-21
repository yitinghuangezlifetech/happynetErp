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
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('version_no', 120)->nullable()->comment('版本號');
            $table->string('name', 120)->nullable()->comment('合約名稱');
            $table->string('plan_type_id', 36)->nullable()->comment('方案類別');
            $table->string('apply_type_id', 36)->nullable()->comment('申請類別');
            $table->string('identity_id', 36)->nullable()->comment('對象');
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式');
            $table->tinyInteger('status')->nullable()->default(2)->comment('狀態(值1:啟用, 值2:停用)');
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
        Schema::dropIfExists('contracts');
    }
};
