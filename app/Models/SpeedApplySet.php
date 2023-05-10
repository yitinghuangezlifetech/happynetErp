<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeedApplySet extends AbstractModel
{
    protected $table = 'speed_apply_sets';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '申請項目名稱',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'申請項目名稱請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
            ],
        ];
    }
}
