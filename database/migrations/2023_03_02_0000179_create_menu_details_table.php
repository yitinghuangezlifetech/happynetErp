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
        Schema::create('menu_details', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade')
                ->comment('目錄ID');
            $table->string('field', 100)->nullable()->comment('欄位名稱');
            $table->string('field_id', 100)->nullable()->comment('欄位id, for對應js id使用');
            $table->string('type', 20)->nullable()->comment('欄位屬性');
            $table->string('show_name', 100)->nullable()->comment('顥示名稱');
            $table->string('note', 100)->nullable()->comment('備註');
            $table->tinyInteger('use_edit_link')->default(2)->nullable()->comment('是否使用編輯連結(值1:是, 值2否)');
            $table->tinyInteger('use_component')->default(2)->nullable()->comment('是否使用元件(值1:是, 值2否)');
            $table->string('component_name', 100)->nullable()->comment('元件名稱');
            $table->tinyInteger('super_admin_use')->default(2)->nullable()->comment('是否超級使用者專用(值1:是, 值2否)');
            $table->tinyInteger('show_hidden_field')->default(2)->nullable()->comment('是否開啟隱藏欄位(值1:是, 值2否)');
            $table->tinyInteger('join_search')->default(2)->nullable()->comment('是否加入搜尋條件(值1:是, 值2否)');
            $table->tinyInteger('required')->default(2)->nullable()->comment('是否必填(值1:是, 值2否)');
            $table->tinyInteger('browse')->default(2)->nullable()->comment('是否顥示在列表頁(值1:是, 值2否)');
            $table->tinyInteger('create')->default(2)->nullable()->comment('是否顥示在新增頁(值1:是, 值2否)');
            $table->tinyInteger('edit')->default(2)->nullable()->comment('是否顥示在編輯頁(值1:是, 值2否)');
            $table->tinyInteger('has_js')->default(2)->nullable()->comment('是否有js(值1:是, 值2否)');
            $table->tinyInteger('has_relationship')->default(2)->nullable()->comment('是否該欄位跟其它表有關聯(值1:是, 值2否)');
            $table->string('relationship_method', 30)->nullable()->comment('關聯的method');
            $table->string('relationship_foreignkey', 30)->nullable()->comment('關聯的foreignkey');
            $table->string('foreign_key', 30)->nullable()->comment('本資料表與其它資料表關聯的欄位名稱');
            $table->tinyInteger('is_virtual_field')->default(2)->nullable()->comment('是否是虛擬欄位(值1:是, 值2否)');
            $table->json('relationship')->nullable()->comment('關聯內容');
            $table->json('attributes')->nullable()->comment('欄位的attribute設定');
            $table->json('options')->nullable()->comment('選項項目');
            $table->json('create_rule')->nullable()->comment('建立資料時的檢驗欄位設定');
            $table->json('update_rule')->nullable()->comment('更新資料時的檢驗欄位設定');
            $table->json('error_msg')->nullable()->comment('檢驗失敗時的錯誤訊息');
            $table->tinyInteger('sort')->nullable()->default(0)->comment('欄位排序');
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
        Schema::dropIfExists('menu_details');
    }
};
