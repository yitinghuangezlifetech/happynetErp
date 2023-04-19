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
            $table->string('apply_type_id', 36)->nullable()->comment('申請類別');
            $table->string('close_period_id', 36)->nullable()->comment('結算區間');
            $table->string('service_type_id', 36)->nullable()->comment('服務類別');
            $table->date('effective_day')->nullable()->comment('生效日期');
            $table->date('expiration_day')->nullable()->comment('截止日期');
            $table->string('item_type_id', 36)->nullable()->comment('品項類別');
            $table->string('product_id', 36)->nullable()->comment('商品品項');
            $table->tinyInteger('pay_way')->nullable()->comment('付款方式(值1:先付, 值2:後付)');
            $table->integer('month_total')->nullable()->default(0)->comment('月繳合計');
            $table->integer('deposit_total')->nullable()->default(0)->comment('保證金合計');
            $table->string('discount_set', 15)->nullable()->default(0)->comment('折抵設定');
            $table->tinyInteger('promotion_type')->nullable()->comment('優惠類型(值1:金額, 值2:百分比)');
            $table->string('discount', 15)->nullable()->default(0)->comment('優惠(元/%)');
            $table->string('sender', 30)->nullable()->comment('送件人');
            $table->string('agent_code', 30)->nullable()->comment('經銷代碼');
            $table->string('tel', 20)->nullable()->comment('聯絡電話');
            $table->string('recipient', 30)->nullable()->comment('收件人');
            $table->string('engineer', 30)->nullable()->comment('技術員');
            $table->string('auditor', 30)->nullable()->comment('稽核人');
            $table->string('customer_no', 30)->nullable()->comment('用戶編號');
            $table->string('customer_name', 120)->nullable()->comment('用戶名稱');
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
