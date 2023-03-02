<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionnaire extends AbstractModel {
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaires';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'questionnaire_event_id',
                'type' => 'select',
                'show_name' => '所屬問卷活動',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\QuestionnaireEvent',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '問卷名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 2
            ],
        ];
    }

    public function topics() {
        return $this->hasMany(QuestionnaireTopic::class, 'questionnaire_id')->orderBy('sort', 'ASC');
    }
}
