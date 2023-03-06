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
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade')
                ->comment('所屬群組');
            $table->string('name', 120)->comment('角色名稱');
            $table->tinyInteger('has_audit_route')->default(2)->nullable()->comment('是否看到所有稽核行程(值1:是, 值2:否)');
            $table->tinyInteger('super_admin')->default(2)->nullable()->comment('是否為超級使用者(值1:是, 值2:否)');
            $table->softDeletes();
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
        Schema::dropIfExists('roles');
    }
};
