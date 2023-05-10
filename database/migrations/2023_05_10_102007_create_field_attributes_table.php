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
        Schema::create('field_attributes', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name', 60)->nullable()->default('屬性名稱');
            $table->string('val', 60)->nullable()->default('屬性值');
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
        Schema::dropIfExists('field_attributes');
    }
};
