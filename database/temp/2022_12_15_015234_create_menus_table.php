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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('menu_name', 60)->nullable()->comment('目錄項目名稱');
            $table->string('name', 60)->nullable()->comment('簡化項目名稱');
            $table->string('slug', 60)->nullable()->comment('slug');
            $table->string('target', 20)->default('_self')->nullable()->comment('開啟連結方式');
            $table->string('icon_class', 60)->nullable()->comment('icon_class');
            $table->string('model', 60)->nullable()->comment('model');
            $table->string('controller', 60)->nullable()->comment('controller');
            $table->tinyInteger('seo_enable')->default(2)->nullable()->comment('是否開啟用SEO功能(值1:是, 值2:否)');
            $table->tinyInteger('sortable_enable')->default(2)->nullable()->comment('是否開啟排序功能(值1:是, 值2:否)');
            $table->tinyInteger('search_component')->default(2)->nullable()->comment('是否開啟搜尋bar(值1:是, 值2:否)');
            $table->tinyInteger('export_data')->default(2)->nullable()->comment('是否啟用資料匯出功能(值1:是, 值2:否)');
            $table->tinyInteger('no_show')->default(2)->nullable()->comment('是否不顥示於目錄(值1:是, 值2:否)');
            $table->string('parent_id', 60)->nullable()->comment('父層目錄');
            $table->integer('sort')->default(0)->nullable()->comment('排序');
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
        Schema::dropIfExists('menus');
    }
};
