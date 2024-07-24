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
        Schema::create('fee_rate_tables', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('fee_rate_id', 36)->nullable()->comment('費率主檔');
            $table->string('type', 20)->default('1')->comment('1:國際,0:國內');
            $table->string('eng_country_name', 120)->nullable()->comment('英文國名');
            $table->string('country_name', 50)->nullable()->comment('國家名稱');
            $table->string('country_code', 20)->nullable()->comment('國碼');
            $table->string('area_code', 20)->nullable()->comment('前置碼');
            $table->string('area_name', 50)->nullable()->comment('區域名稱');
            $table->integer('unit')->nullable()->comment('第1段單位(秒)');
            $table->double('price')->nullable()->comment('第1段價格');
            $table->integer('unit_2')->default(0)->comment('第2段單位(秒)');
            $table->double('price_2')->default(0)->comment('第2段價格');
            $table->string('created_id', 33)->default('admin')->comment('建立人員');
            $table->string('updated_id', 33)->default('admin')->comment('修改人員');
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
        Schema::dropIfExists('fee_rate_tables');
    }
};
