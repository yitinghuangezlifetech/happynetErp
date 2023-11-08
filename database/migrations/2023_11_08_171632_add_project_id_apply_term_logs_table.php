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
        Schema::table('apply_term_logs', function (Blueprint $table) {
            $table->string('project_id', 36)->nullable()->after('contract_id')->comment('專案Id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apply_term_logs', function (Blueprint $table) {
            //
        });
    }
};
