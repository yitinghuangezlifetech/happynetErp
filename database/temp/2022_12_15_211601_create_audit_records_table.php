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
        Schema::create('audit_records', function (Blueprint $table) {
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
            $table->foreignUuid('main_attribute_id')
                ->references('id')
                ->on('main_attributes')
                ->onDelete('cascade')
                ->comment('屬性');
            $table->foreignUuid('sub_attribute_id')
                ->references('id')
                ->on('sub_attributes')
                ->onDelete('cascade')
                ->comment('次屬性');
            $table->foreignUuid('regulation_id')
                ->references('id')
                ->on('regulations')
                ->onDelete('cascade')
                ->comment('條文');
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->comment('稽核人員id');    
            $table->foreignUuid('audit_fail_type_id')
                ->nullable()
                ->references('id')
                ->on('audit_fail_types')
                ->onDelete('cascade')
                ->comment('缺失判定id');    
            $table->tinyInteger('status')->default(0)->nullable()->comment('稽核狀態(值0: 未檢查, 值1:合格, 值2:不合格, 值3:不適用)');
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
        Schema::dropIfExists('audit_records');
    }
};
