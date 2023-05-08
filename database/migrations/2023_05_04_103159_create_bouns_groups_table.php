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
        Schema::create('bouns_groups', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('group_no', 30)->nullable()->comment('群組編號');
            $table->string('name', 120)->nullable()->comment('群組名稱');
            $table->string('parent_id', 36)->nullable()->comment('父層id');
            $table->integer('sort')->nullable()->default(0)->comment('排序值');
            $table->tinyInteger('status')->nullable()->default(1)->comment('狀態(值1:啟用, 值2:停用)');
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
        Schema::dropIfExists('bouns_groups');
    }
};
