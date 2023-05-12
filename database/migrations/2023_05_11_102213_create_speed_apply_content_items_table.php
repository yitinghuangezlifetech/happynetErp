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
        Schema::create('speed_apply_content_items', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('speed_apply_set_id', 36)->nullable()->comment('快速申請主檔id');
            $table->string('speed_apply_content_id', 36)->nullable()->comment('快速申請內容主檔id');
            $table->string('name', 160)->nullable()->comment('項目名稱');
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
        Schema::dropIfExists('speed_apply_content_items');
    }
};
