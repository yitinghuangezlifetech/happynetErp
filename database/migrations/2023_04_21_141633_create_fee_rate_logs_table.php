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
        Schema::create('fee_rate_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('fee_rate_id', 36)->nullable()->comment('費率主檔');
            $table->string('call_target_id', 36)->nullable()->comment('撥打對象');
            $table->string('call_rate', 20)->nullable()->comment('通話費率');
            $table->string('discount', 10)->nullable()->comment('折讓');
            $table->string('discount_after_rate', 10)->nullable()->comment('折後費率');
            $table->tinyInteger('charge_unit')->nullable()->default(1)->comment('計費單位(值1:秒鐘, 值2:分鐘)');
            $table->tinyInteger('include_tax')->nullable()->default(2)->comment('含稅(值1:是, 值2:否)');
            $table->string('parameter', 10)->nullable()->comment('參數');
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
        Schema::dropIfExists('fee_rate_logs');
    }
};
