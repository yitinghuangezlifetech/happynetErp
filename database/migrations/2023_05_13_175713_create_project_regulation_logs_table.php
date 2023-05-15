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
        Schema::create('project_regulation_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('project_id', 36)->nullable()->comment('所屬專案id');
            $table->string('project_regulation_id', 36)->nullable()->comment('所屬專案條文id');
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
        Schema::dropIfExists('project_regulation_logs');
    }
};
