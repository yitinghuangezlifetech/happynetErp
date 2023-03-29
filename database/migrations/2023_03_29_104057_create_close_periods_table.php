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
        Schema::create('close_periods', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name', 120)->nullable()->comment('結算區間名稱');
            $table->integer('bill_day')->nullable()->comment('結算日');
            $table->integer('month_range')->nullable()->comment('結算月數');
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
        Schema::dropIfExists('close_periods');
    }
};
