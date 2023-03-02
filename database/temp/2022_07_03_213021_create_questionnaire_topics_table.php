<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnaireTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_topics', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('questionnaire_id')
                ->references('id')
                ->on('questionnaires')
                ->onDelete('cascade')
                ->comment('所屬問卷id');
            $table->string('topic_type', 60)->nullable()->comment('問題屬性');
            $table->text('subject')->nullable()->comment('題目');
            $table->tinyInteger('sort')->default(0)->nullabel()->comment('排序');
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
        Schema::dropIfExists('questionnaire_topics');
    }
}
