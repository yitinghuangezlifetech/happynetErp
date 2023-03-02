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
        Schema::create('audit_record_successes', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('audit_route_id')
                ->references('id')
                ->on('audit_routes')
                ->onDelete('cascade')
                ->comment('稽核行程id');
            $table->foreignUuid('audit_record_id')
                ->references('id')
                ->on('audit_records')
                ->onDelete('cascade')
                ->comment('稽核主檔id');
            $table->string('img', 120)->nullable()->comment('合格照片');
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
        Schema::dropIfExists('audit_record_successes');
    }
};
