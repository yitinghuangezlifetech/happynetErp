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
        Schema::create('pending_check_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('pending_check_id')
                ->nullable()
                ->references('id')
                ->on('pending_checks')
                ->onDelete('set null')
                ->comment('所屬待稽核主檔');
            $table->foreignUuid('audit_record_fail_id')
                ->nullable()
                ->references('id')
                ->on('audit_record_fails')
                ->onDelete('set null')
                ->comment('所屬缺失紀錄');
            $table->foreignUuid('regulation_fact_id')
                ->nullable()
                ->references('id')
                ->on('regulation_facts')
                ->onDelete('cascade')
                ->comment('條文附屬所見事實項目id');
            $table->string('img', 120)->nullable()->comment('缺失照片');
            $table->text('note')->nullable()->comment('備註');
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
        Schema::dropIfExists('pending_check_logs');
    }
};
