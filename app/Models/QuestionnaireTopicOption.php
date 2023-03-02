<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireTopicOption extends AbstractModel {
    use HasFactory;

    protected $table = 'questionnaire_topic_options';
    protected $guarded = [];

    public function getFieldProperties() {}
}
