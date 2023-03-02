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
        Schema::create('audit_route_store_imgs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('audit_route_id')
                ->references('id')
                ->on('audit_routes')
                ->onDelete('cascade')
                ->comment('行程ID');
            $table->foreignUuid('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->comment('店家ID'); 
            $table->string('img', 120)->nullable()->comment('圖檔路徑');
            $table->tinyInteger('is_main')->nullable()->default(2)->comment('是否設為主要照片(值1:是, 值2:否)');
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
        Schema::dropIfExists('audit_route_store_imgs');
    }
};
