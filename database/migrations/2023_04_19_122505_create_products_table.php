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
            $table->string('product_type_id', 36)->nullable()->comment('商品類型');
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式');
            $table->string('fee_rate_id', 36)->nullable()->comment('費率模組');
            $table->string('name', 120)->nullable()->comment('商品名稱');
            $table->integer('price')->nullable()->default(0)->comment('商品價格');
            $table->integer('rent_month')->nullable()->default(0)->comment('月租費用');
            $table->integer('deposit_amount')->nullable()->default(0)->comment('保證金');
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
