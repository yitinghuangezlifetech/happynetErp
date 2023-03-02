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
        Schema::create('audit_record_items', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('audit_record_id')
                ->references('id')
                ->on('audit_records')
                ->onDelete('cascade')
                ->comment('稽核主檔id'); 
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
        Schema::dropIfExists('audit_record_items');
    }
};
