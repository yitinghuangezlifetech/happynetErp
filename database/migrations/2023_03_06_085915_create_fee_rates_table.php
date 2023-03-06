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
        Schema::create('fee_rates', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('identity_id')
                ->references('id')
                ->on('identities')
                ->onDelete('cascade')
                ->comment('所屬身份');
            $table->foreignUuid('service_type_id')
                ->references('id')
                ->on('service_types')
                ->onDelete('cascade')
                ->comment('所屬服務類別');
            $table->foreignUuid('fee_rate_type_id')
                ->references('id')
                ->on('fee_rate_types')
                ->onDelete('cascade')
                ->comment('費率類別');
            $table->integer('unit')->default(0)->nullable()->comment('計費單位(秒)');
            $table->integer('fee')->default(0)->nullable()->comment('資費');
            $table->tinyInteger('status')->default(2)->nullbale()->comment('狀態(值1:啟用, 值2:停用)');
            $table->string('create_user_id', 36)->nullable()->comment('建立人員');
            $table->string('update_user_id', 36)->nullable()->comment('修改人員');
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
        Schema::dropIfExists('fee_rates');
    }
};
