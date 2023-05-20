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
            $table->string('contract_no', 30)->nullable()->comment('合約編號');
            $table->string('name', 120)->nullable()->comment('合約名稱');
            $table->string('project_id', 36)->nullable()->comment('適用專案');
            $table->string('identity_id', 36)->nullable()->comment('對象');
            $table->tinyInteger('type')->nullable()->comment('類型(值1:一般, 值2:範本)');
            $table->string('version_no', 30)->nullable()->comment('版本號');
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式');
            $table->string('product_type_id', 36)->nullable()->comment('商品類別');
            $table->tinyInteger('pay_way')->nullable()->comment('付款方式(值1:先付, 值2:後付)');
            $table->tinyInteger('promotion_type')->nullable()->comment('優惠類型(值1:金額, 值2:百分比)');
            $table->string('discount', 15)->nullable()->default(0)->comment('優惠(元/%)');
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
