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
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade')
                  ->comment('所屬後台功能項目');
            $table->string('code', 100)->nullable()->comment('權限代碼');
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
        Schema::dropIfExists('permissions');
    }
};
