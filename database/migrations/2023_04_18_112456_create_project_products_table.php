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
        Schema::create('project_products', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('project_id', 36)->nullable()->comment('所屬專案');
            $table->string('product_type_id', 36)->nullable()->comment('所屬商品類型');
            $table->string('product_id', 36)->nullable()->comment('所屬商品');
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
        Schema::dropIfExists('project_products');
    }
};
