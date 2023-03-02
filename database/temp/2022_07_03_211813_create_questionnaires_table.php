<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('questionnaire_event_id')
                ->references('id')
                ->on('questionnaire_events')
                ->onDelete('cascade')
                ->comment('所屬問卷活動id');
            $table->string('name', 120)->nullable()->comment('問卷名稱');
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
        Schema::dropIfExists('questionnaires');
    }
}
