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
        Schema::create('regulation_fails', function (Blueprint $table) {
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
            $table->foreignUuid('audit_fail_type_id')
                ->references('id')
                ->on('audit_fail_types')
                ->onDelete('cascade')
                ->comment('所屬缺失類型id');
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
        Schema::dropIfExists('regulation_fails');
    }
};
