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
        Schema::create('regulations', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('system_type_id')
                ->references('id')
                ->on('system_types')
                ->onDelete('cascade')
                ->comment('所屬系統類別');
            $table->foreignUuid('main_attribute_id')
                ->references('id')
                ->on('main_attributes')
                ->onDelete('cascade');
            $table->foreignUuid('sub_attribute_id')
                ->references('id')
                ->on('sub_attributes')
                ->onDelete('cascade');
            $table->integer('no')->nullable()->comment('條文項目(ex:1, 2, 3, 4...)');
            $table->text('clause')->nullable()->comment('條文');
            $table->tinyInteger('level')->default(1)->nullable()->comment('等級(值1:普, 值2:中, 值3:高)');
            $table->tinyInteger('type')->default(1)->nullable()->comment('類型(值1:大型, 值2:小型)');
            $table->tinyInteger('is_main')->default(2)->nullable()->comment('是否是主要條文(值1:是, 值2:否)');
            $table->tinyInteger('is_import')->nullable()->comment('重點條文(值1:是, 值2:否)');
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
        Schema::dropIfExists('regulations');
    }
};
