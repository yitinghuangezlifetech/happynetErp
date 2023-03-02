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
        Schema::create('pending_check_items', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('pending_check_id')
                ->nullable()
                ->references('id')
                ->on('pending_checks')
                ->onDelete('set null')
                ->comment('所屬待稽核主檔');
            $table->foreignUuid('regulation_item_id')
                ->references('id')
                ->on('regulation_items')
                ->onDelete('cascade')
                ->comment('條文附屬項目id');
            $table->string('value', 50)->nullable()->comment('紀錄數值');
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
        Schema::dropIfExists('pending_check_items');
    }
};
