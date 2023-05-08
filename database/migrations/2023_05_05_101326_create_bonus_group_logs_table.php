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
        Schema::create('bonus_group_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('bonus_group_id', 36)->nullable()->comment('所屬奬金群組id');
            $table->string('func_type_id', 36)->nullable()->comment('所屬奬金類別id');
            $table->string('bonus', 10)->nullable()->comment('奬金%數');
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
        Schema::dropIfExists('bonus_group_logs');
    }
};
