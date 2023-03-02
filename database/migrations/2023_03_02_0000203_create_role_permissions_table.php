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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade')
                  ->comment('所屬角色');
            $table->foreignUuid('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade')
                  ->comment('所屬權限');
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
        Schema::dropIfExists('role_permissions');
    }
};
