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
        Schema::create('pending_check_results', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('pending_check_id')
                ->nullable()
                ->references('id')
                ->on('pending_checks')
                ->onDelete('set null')
                ->comment('所屬待稽核主檔');
            $table->foreignUuid('pending_check_log_id')
                ->nullable()
                ->references('id')
                ->on('pending_check_logs')
                ->onDelete('set null')
                ->comment('所屬待稽核主檔項細log');
            $table->tinyInteger('record_type')->nullable()->comment('記錄類型(值1:成功紀錄, 值2:失敗紀錄)');
            $table->foreignUuid('regulation_fact_id')
                ->nullable()
                ->references('id')
                ->on('regulation_facts')
                ->onDelete('cascade')
                ->comment('條文附屬所見事實項目id');
            $table->string('img', 120)->nullable()->comment('缺失/合格照片');
            $table->text('note')->nullable()->comment('備註');
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
        Schema::dropIfExists('pending_check_results');
    }
};
