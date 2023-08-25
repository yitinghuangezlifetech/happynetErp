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
        Schema::create('rate_type_ips', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('rate_type_id', 36)->nullable()->comment('費率類別');
            $table->string('ip', 30)->nullable()->comment('ip');
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
        Schema::dropIfExists('rate_type_ips');
    }
};
