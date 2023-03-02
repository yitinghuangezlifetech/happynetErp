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
        Schema::create('role_system_type_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('system_type_id')
                ->nullable()
                ->references('id')
                ->on('system_types')
                ->onDelete('cascade')
                ->comment('所屬系統類別');
            $table->foreignUuid('role_id')
                ->nullable()
                ->references('id')
                ->on('roles')
                ->onDelete('cascade')
                ->comment('所屬角色');
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
        Schema::dropIfExists('role_system_type_logs');
    }
};
