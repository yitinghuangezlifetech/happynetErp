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
        Schema::table('bouns_groups', function (Blueprint $table) {
            $table->string('organization_id', 36)->nullable()->after('id')->comment('所屬組織');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bouns_groups', function (Blueprint $table) {
            //
        });
    }
};
