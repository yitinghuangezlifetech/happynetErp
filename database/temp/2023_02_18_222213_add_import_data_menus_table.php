<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportDataMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('export_data_route', 120)->nullable()->after('export_data')->comment('匯出資料的route設定');
            $table->tinyInteger('import_data')->nullable()->default(2)->after('export_data_route')->comment('啟用資料匯入(值1:是, 值2:否)');
            $table->string('import_data_route', 120)->nullable()->after('import_data')->comment('匯入資料的route設定');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            //
        });
    }
}
