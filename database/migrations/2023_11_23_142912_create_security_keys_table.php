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
        Schema::create('security_keys', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('system_service_id', 36)->nullable()->comment('服務別');
            $table->string('organization_id', 36)->nullable()->comment('所屬組織');
            $table->string('security_key', 160)->nullable()->comment('金鑰');
            $table->date('start_day')->nullable()->comment('金鑰生效日');
            $table->date('end_day')->nullable()->comment('金鑰失效日');
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
        Schema::dropIfExists('security_keys');
    }
};
