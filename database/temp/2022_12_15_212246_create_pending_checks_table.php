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
        Schema::create('pending_checks', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('audit_route_id')
                ->references('id')
                ->on('audit_routes')
                ->onDelete('cascade')
                ->comment('所屬稽核行程');
            $table->foreignUuid('main_user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->comment('主稽核人員');
            $table->foreignUuid('sub_user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->comment('副稽核人員');
            $table->foreignUuid('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->comment('稽核店家');
            $table->foreignUuid('fail_type_id')
                ->references('id')
                ->on('audit_fail_types')
                ->onDelete('cascade')
                ->comment('缺失判定id');
            $table->foreignUuid('regulation_id')
                ->references('id')
                ->on('regulations')
                ->onDelete('cascade')
                ->comment('條文');
            $table->date('audit_day')->nullable()->comment('稽核日期');
            $table->tinyInteger('status')->default(0)->nullable()->comment('狀態(值0:未開始, 值1:合格, 值2:不合格)');
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
        Schema::dropIfExists('pending_checks');
    }
};
