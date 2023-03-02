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
        Schema::create('sub_attributes', function (Blueprint $table) {
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
            $table->string('name')->command('次屬性名稱');
            $table->tinyInteger('type')->default(1)->nullable()->comment('類型(值1:大型, 值2:小型)');
            $table->string('law_source', 150)->nullable()->comment('法源');
            $table->tinyInteger('sort')->nullable()->default(0)->comment('排序');
            $table->softDeletes();    
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
        Schema::dropIfExists('sub_attributes');
    }
};
