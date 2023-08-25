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
        Schema::table('dial_records', function (Blueprint $table) {
            $table->string('rate_type_id', 36)->nullable()->after('batch_no')->comment('所屬費率類別');
            $table->string('organization_id', 36)->nullable()->after('rate_type_id')->comment('所屬組織');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dial_records', function (Blueprint $table) {
            //
        });
    }
};
