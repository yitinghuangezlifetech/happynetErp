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
        Schema::create('apply_fee_rate_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('apply_id', 36)->nullable()->comment('申請書id');
            $table->string('apply_product_log_id', 36)->nullable()->comment('商品紀錄id');
            $table->string('call_target_id', 36)->nullable()->comment('撥打對象id');
            $table->string('call_rate', 10)->nullable()->comment('通話費率');
            $table->string('discount', 5)->nullable()->comment('折讓(%)');
            $table->string('amount', 10)->nullable()->comment('折後費率');
            $table->tinyInteger('charge_unit')->nullable()->comment('計價單位(值1:秒鐘, 值2:分鐘)');
            $table->integer('parameter')->nullable()->default(0)->comment('參數');
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
        Schema::dropIfExists('apply_fee_rate_logs');
    }
};
