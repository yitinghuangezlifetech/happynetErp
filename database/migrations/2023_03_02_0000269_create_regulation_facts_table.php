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
        Schema::create('regulation_facts', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('system_type_id')
                ->references('id')
                ->on('system_types')
                ->onDelete('cascade')
                ->comment('所屬系統類別');
            $table->foreignUuid('regulation_id')
                ->references('id')
                ->on('regulations')
                ->onDelete('cascade')
                ->comment('所屬條文id');
            $table->string('name', 100)->nullable()->comment('條文附屬所見事實之選項項目名稱');
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
        Schema::dropIfExists('regulation_facts');
    }
};
