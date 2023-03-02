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
        Schema::table('audit_routes', function (Blueprint $table) {
            $table->tinyInteger('enable_routine')->default(2)->nullable()->after('audit_day')->comment('是否啟用例行行程(值1:是, 值2:否)');
            $table->tinyInteger('routine_option')->default(2)->nullable()->after('enable_routine')->comment('例行定義(值1:每週, 值2:每月, 值3:每年)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_routes', function (Blueprint $table) {
            //
        });
    }
};
