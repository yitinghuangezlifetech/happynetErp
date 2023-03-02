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
            $table->string('system_type_id', 36)->nullable()->after('id')->comment('所屬系統類別');
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
