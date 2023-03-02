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
        Schema::create('system_type_colums', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('system_type_id')
                ->nullable()
                ->references('id')
                ->on('system_types')
                ->onDelete('cascade')
                ->comment('所屬系統類別');
            $table->string('field', 30)->nullable()->comment('資料表欄位');
            $table->string('name', 60)->nullable()->comment('顯示名稱');
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
        Schema::dropIfExists('system_type_colums');
    }
};
