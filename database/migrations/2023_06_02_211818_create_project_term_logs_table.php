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
        Schema::create('project_term_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('project_id', 36)->nullable()->comment('專案id');
            $table->string('term_id', 36)->nullable()->comment('條文id');
            $table->integer('sort')->nullable()->default(0)->comment('排序(由小到大)');
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
        Schema::dropIfExists('project_term_logs');
    }
};
