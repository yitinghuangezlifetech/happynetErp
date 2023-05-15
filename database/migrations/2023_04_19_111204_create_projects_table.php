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
            $table->string('name', 120)->nullable()->comment('專案名稱');
            $table->string('identity_id', 36)->nullable()->comment('對象');
            $table->tinyInteger('type')->nullable()->comment('類型(值1:一般, 值2:範本)');
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式');
            $table->string('apply_type_id', 36)->nullable()->comment('申請類別');
            $table->string('close_period_id', 36)->nullable()->comment('結算區間');
            $table->string('product_type_id', 36)->nullable()->comment('商品類別');
            $table->date('effective_day')->nullable()->comment('生效日期');
            $table->date('expiration_day')->nullable()->comment('截止日期');
            $table->tinyInteger('pay_way')->nullable()->comment('付款方式(值1:先付, 值2:後付)');
            $table->string('discount_set', 15)->nullable()->default(0)->comment('折抵設定');
            $table->string('call_rate', 15)->nullable()->default(0)->comment('通話費率');
            $table->tinyInteger('promotion_type')->nullable()->comment('優惠類型(值1:金額, 值2:百分比)');
            $table->string('discount', 15)->nullable()->default(0)->comment('優惠(元/%)');
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
