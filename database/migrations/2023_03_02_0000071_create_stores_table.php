<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('store_type_id')
                ->nullable()
                ->references('id')
                ->on('store_types')
                ->onDelete('set null');
            $table->foreignUuid('store_industry_id')
                ->nullable()
                ->references('id')
                ->on('store_industries')
                ->onDelete('set null');
            $table->string('name', 100)->command('商家名稱');
            $table->string('food_no', 30)->nullable()->command('食品業者登錄字號');
            $table->string('year', 6)->nullable()->command('年度');
            $table->string('manager', 20)->nullable()->command('負責人');
            $table->string('sex', 2)->default('m')->nullable()->command('性別(值m:男性, 值f:女性)');
            $table->string('phone', 30)->nullable()->command('聯絡電話');
            $table->string('address', 160)->nullable()->command('地址');
            $table->softDeletes();
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
        Schema::dropIfExists('stores');
    }
}
