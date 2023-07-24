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
            $table->tinyInteger('show')->nullable()->default(2)->after('edit_show')->comment('是否顥示在檢視頁(值1:是, 值2否)');
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
