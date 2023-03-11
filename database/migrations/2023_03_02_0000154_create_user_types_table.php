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
        Schema::create('user_types', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name', 120)->nullable()->comment('用戶類別名稱');
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
        Schema::dropIfExists('user_types');
    }
};
