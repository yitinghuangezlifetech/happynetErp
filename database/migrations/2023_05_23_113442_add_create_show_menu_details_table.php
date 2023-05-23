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
        Schema::table('menu_details', function (Blueprint $table) {
            $table->tinyInteger('create_show')->nullable()->default(1)->after('create')->comment('是否在新增頁顥示欄位(值1:是, 值2:否)');
            $table->tinyInteger('edit_show')->nullable()->default(1)->after('edit')->comment('是否在編輯頁顥示欄位(值1:是, 值2:否)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_details', function (Blueprint $table) {
            //
        });
    }
};
