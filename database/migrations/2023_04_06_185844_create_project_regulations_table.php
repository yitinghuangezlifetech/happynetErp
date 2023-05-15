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
        Schema::create('project_regulations', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('sales_type_id', 36)->nullable()->comment('銷售模式id');
            $table->string('product_type_id', 36)->nullable()->comment('商品類別id');
            $table->string('name')->nullable()->comment('條文名稱');
            $table->text('content')->nullable()->comment('條文內容');
            $table->tinyInteger('status')->nullable()->default(1)->comment('狀態(值1:啟用, 值2:停用)');
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
        Schema::dropIfExists('project_regulations');
    }
};
