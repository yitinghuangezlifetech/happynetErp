<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireTopic extends AbstractModel {
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaire_topics';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'questionnaire_id',
                'type' => 'select',
                'show_name' => '所屬問卷',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Questionnaire',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'topic_type_id',
                'type' => 'select',
                'show_name' => '題目屬性',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\TopicType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'subject',
                'type' => 'text',
                'show_name' => '題目',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 3
            ],
        ];
    }

    public function options() {
        return $this->hasMany(QuestionnaireTopicOption::class, 'questionnaire_topic_id');
    }
}
