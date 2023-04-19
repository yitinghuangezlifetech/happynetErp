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
        Schema::create('doc_manages', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->integer('version_times')->nullable()->default(0)->comment('版次');
            $table->string('contract_no', 30)->nullable()->comment('合約編號');
            $table->string('contract_name', 120)->nullable()->comment('合約名稱');
            $table->string('customer_no', 30)->nullable()->comment('用戶編號');
            $table->string('contract_file', 120)->nullable()->comment('合約檔案');
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
        Schema::dropIfExists('doc_manages');
    }
};
