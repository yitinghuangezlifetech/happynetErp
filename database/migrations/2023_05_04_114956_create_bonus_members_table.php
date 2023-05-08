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
        Schema::create('bonus_members', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('bonus_group_id', 36)->nullable()->comment('所屬奬金群組id');
            $table->string('name', 60)->nullable()->comment('名稱');
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
        Schema::dropIfExists('bonus_members');
    }
};
