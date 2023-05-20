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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('project_no', 30)->nullable()->comment('專案編號');
            $table->string('code', 30)->nullable()->comment('專案代碼');
            $table->string('name', 120)->nullable()->comment('專案名稱');
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式');
            $table->string('apply_type_id', 36)->nullable()->comment('申請類別');
            $table->string('close_period_id', 36)->nullable()->comment('結算區間');
            $table->tinyInteger('pay_way')->nullable()->comment('付款方式(值1:先付, 值2:後付)');
            $table->date('effective_day')->nullable()->comment('生效日期');
            $table->date('expiration_day')->nullable()->comment('截止日期');
            $table->tinyInteger('status')->nullable()->default(2)->comment('狀態(值1:啟用, 值2:停用)');
            $table->dateTime('invalid_date')->nullable()->comment('停用日期');
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
        Schema::dropIfExists('projects');
    }
};
