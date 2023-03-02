<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_types', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name', 120)->nullable()->comment('屬性名稱'); 
            $table->string('val', 120)->nullable()->comment('屬性值'); 
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
        Schema::dropIfExists('topic_types');
    }
}
