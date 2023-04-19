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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('product_category', 120)->nullable()->comment('商品類型');
            $table->string('product_type_id', 36)->nullable()->comment('品項類別');
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式');
            $table->string('name', 120)->nullable()->comment('產品名稱');
            $table->string('model_no', 120)->nullable()->comment('產品型號');
            $table->string('spec', 120)->nullable()->comment('產品規格');
            $table->tinyInteger('charge_type')->nullable()->comment('收費方式');
            $table->integer('price')->nullable()->default(0)->comment('產品原價');
            $table->string('rate_no', 30)->nullable()->comment('費率編號');
            $table->string('discount_rate', 30)->nullable()->comment('折後費率');
            $table->tinyInteger('status')->nullable()->default(2)->comment('狀態(值1:啟用, 值2:停用)');
            $table->string('create_user_id', 36)->nullable()->comment('建立人員');
            $table->string('update_user_id', 36)->nullable()->comment('修改人員');
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
        Schema::dropIfExists('products');
    }
};
