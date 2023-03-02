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
        Schema::create('audit_routes', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
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
            $table->date('audit_day')->nullable()->comment('稽核日期');
            $table->string('main_user_signe')->nullable()->comment('輔導委員一簽名檔');
            $table->string('sub_user_signe')->nullable()->comment('輔導委員二簽名檔');
            $table->string('store_signe')->nullable()->comment('業者簽名檔');
            $table->string('gov_signe')->nullable()->comment('衛生局人員簽名檔');
            $table->text('note')->nullable()->comment('稽核註記');
            $table->string('report', 120)->nullable()->comment('稽核報告書');
            $table->tinyInteger('audit_status')->nullable()->comment('稽核狀態(值0:未開始, 值1:輔導, 值2:評核, 值3:追評)');
            $table->tinyInteger('status')->nullable()->comment('狀態(值0:未開始, 值1:開始 值2:稽核中, 值3:開始上傳, 值4:上傳完成)');
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
        Schema::dropIfExists('audit_routes');
    }
};
