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
        Schema::create('contract_product_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('contract_id', 36)->nullable()->comment('合約id');
            $table->string('product_id', 36)->nullable()->comment('商品id');
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
        Schema::dropIfExists('contract_product_logs');
    }
};
