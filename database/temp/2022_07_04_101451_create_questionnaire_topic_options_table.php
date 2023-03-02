<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnaireTopicOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_topic_options', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('questionnaire_id')
                ->references('id')
                ->on('questionnaires')
                ->onDelete('cascade')
                ->comment('所屬問卷id');
            $table->foreignUuid('questionnaire_topic_id')
                ->references('id')
                ->on('questionnaire_topics')
                ->onDelete('cascade')
                ->comment('所屬問題id');
            $table->string('name', 120)->nullable()->comment('選項名稱');
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
        Schema::dropIfExists('questionnaire_topic_options');
    }
}
