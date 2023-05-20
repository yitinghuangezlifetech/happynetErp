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
        Schema::create('terms', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('term_type_id', 36)->nullable()->comment('條文類型');
            $table->string('sales_type_id', 36)->nullable()->comment('適用銷售模式');
            $table->string('product_type_id', 36)->nullable()->comment('適用商品類型');
            $table->string('title', 120)->nullable()->comment('主標題');
            $table->string('describe')->nullable()->comment('標題說明');
            $table->text('content')->nullable()->comment('條文內容');
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
        Schema::dropIfExists('terms');
    }
};
