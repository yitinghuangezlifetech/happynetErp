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
        Schema::table('func_types', function (Blueprint $table) {
            $table->string('rate_type_id', 36)->nullable()->after('parent_id')->comment('所屬費率類別');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('func_types', function (Blueprint $table) {
            //
        });
    }
};
