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
        Schema::create('fee_rates', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('rate_type_id', 36)->nullable()->comment('費率類別');
            $table->string('name', 120)->default(0)->nullable()->comment('費率模組名稱');
            $table->tinyInteger('status')->default(2)->nullbale()->comment('狀態(值1:啟用, 值2:停用)');
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
        Schema::dropIfExists('fee_rates');
    }
};
