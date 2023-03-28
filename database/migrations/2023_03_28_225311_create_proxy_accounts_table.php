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
        Schema::create('proxy_accounts', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('user_id', 36)->nullable()->comment('使用者id');
            $table->string('create_user_id', 36)->nullable()->comment('建立人員');
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
        Schema::dropIfExists('proxy_accounts');
    }
};
