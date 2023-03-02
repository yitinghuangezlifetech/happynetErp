<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireEvent extends AbstractModel {
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaire_events';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '問卷活動名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 1
            ],
        ];
    }
}
