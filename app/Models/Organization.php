<?php

namespace App\Models;

class Organization extends AbstractModel
{
    protected $table = 'organizations';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'system_no',
                'type' => 'text',
                'show_name' => '系統編號',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0
            ],
            [
                'field' => 'group_id',
                'type' => 'select',
                'show_name' => '群組',
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Group',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'identity_id',
                'type' => 'select',
                'show_name' => '身份/層級',
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Identity',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['identity_id.required'=>'請選擇所屬身份']
                ]),
            ],
            [
                'field' => 'organization_type_id',
                'type' => 'select',
                'show_name' => '組織類型',
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\OrganizationType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'organization_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'organization_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['organization_type_id.required'=>'請選擇所屬組織類型']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '名稱',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4
            ],
            [
                'field' => 'cost_rate_id',
                'type' => 'select',
                'show_name' => '成本費率名稱',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\CostRate',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
            ],
            [
                'field' => 'id_no',
                'type' => 'text',
                'show_name' => '統編/身份證號',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 6
            ],
            [
                'field' => 'manager',
                'type' => 'text',
                'show_name' => '負責人',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 7
            ],
            [
                'field' => 'manager_id_no',
                'type' => 'text',
                'show_name' => '負責人身份字號',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 8
            ],
            [
                'field' => 'mobile',
                'type' => 'text',
                'show_name' => '行動電話',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 9
            ],
            [
                'field' => 'tel',
                'type' => 'text',
                'show_name' => '聯絡電話',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 10
            ],
            [
                'field' => 'tel_1',
                'type' => 'text',
                'show_name' => '分機',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 11
            ],
            [
                'field' => 'fax',
                'type' => 'text',
                'show_name' => '傳真電話',
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 12
            ],
            [
                'field' => 'line',
                'type' => 'text',
                'show_name' => 'Line帳號',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 13
            ],
            [
                'field' => 'county',
                'type' => 'text',
                'show_name' => '戶籍-縣市',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 14
            ],
            [
                'field' => 'district',
                'type' => 'text',
                'show_name' => '戶籍-鄉鎮市區',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 15
            ],
            [
                'field' => 'zipcode',
                'type' => 'text',
                'show_name' => '戶籍-郵遞區號',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 16
            ],
            [
                'field' => 'address',
                'type' => 'text',
                'show_name' => '戶籍-地址',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 17
            ],
            [
                'field' => 'bill_county',
                'type' => 'text',
                'show_name' => '帳單-縣市',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 18
            ],
            [
                'field' => 'bill_district',
                'type' => 'text',
                'show_name' => '帳單-鄉鎮市區',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 19
            ],
            [
                'field' => 'bill_zipcode',
                'type' => 'text',
                'show_name' => '帳單-郵遞區號',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 20
            ],
            [
                'field' => 'bill_address',
                'type' => 'text',
                'show_name' => '帳單-地址',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 21
            ],
            [
                'field' => 'business_county',
                'type' => 'text',
                'show_name' => '營業-縣市',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 22
            ],
            [
                'field' => 'business_district',
                'type' => 'text',
                'show_name' => '營業-鄉鎮市區',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 23
            ],
            [
                'field' => 'business_zipcode',
                'type' => 'text',
                'show_name' => '營業-郵遞區號',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 24
            ],
            [
                'field' => 'business_address',
                'type' => 'text',
                'show_name' => '營業-郵遞區號',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 25
            ],
            [
                'field' => 'email',
                'type' => 'email',
                'show_name' => '郵件信箱',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 26
            ],
            [
                'field' => 'official_website',
                'type' => 'text',
                'show_name' => '官方網站',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 27
            ],
            [
                'field' => 'expiry_day',
                'type' => 'date',
                'show_name' => '有效期限',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 28
            ],
            [
                'field' => 'bill_day',
                'type' => 'number',
                'show_name' => '每月結算日',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 29
            ],
            [
                'field' => 'attach_number_limit',
                'type' => 'number',
                'show_name' => '附掛號碼限制數',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 30
            ],
            [
                'field' => 'phone_call_limit',
                'type' => 'number',
                'show_name' => '同時最大通話總數限制',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 31
            ],
            [
                'field' => 'bill_contact',
                'type' => 'text',
                'show_name' => '帳務聯絡人',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 32
            ],
            [
                'field' => 'bill_contact_mobile',
                'type' => 'text',
                'show_name' => '帳務聯絡人-手機',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 33
            ],
            [
                'field' => 'bill_contact_tel',
                'type' => 'text',
                'show_name' => '帳務聯絡人-電話',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 34
            ],
            [
                'field' => 'bill_contact_tel_1',
                'type' => 'text',
                'show_name' => '帳務聯絡人-分機',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 35
            ],
            [
                'field' => 'bill_contact_line',
                'type' => 'text',
                'show_name' => '帳務聯絡人-line',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 36
            ],
            [
                'field' => 'bill_contact_mail',
                'type' => 'text',
                'show_name' => '帳務聯絡人Mail-1',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 37
            ],
            [
                'field' => 'bill_contact_mail_1',
                'type' => 'text',
                'show_name' => '帳務聯絡人Mail-2',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 38
            ],
            [
                'field' => 'setup_contact',
                'type' => 'text',
                'show_name' => '裝機聯絡人',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 39
            ],
            [
                'field' => 'setup_contact_mobile',
                'type' => 'number',
                'show_name' => '裝機聯絡人-手機',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 40
            ],
            [
                'field' => 'setup_contact_tel',
                'type' => 'number',
                'show_name' => '裝機聯絡人-電話',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 41
            ],
            [
                'field' => 'setup_contact_tel_1',
                'type' => 'number',
                'show_name' => '裝機聯絡人-分機',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 42
            ],
            [
                'field' => 'setup_contact_line',
                'type' => 'number',
                'show_name' => '裝機聯絡人-line',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 43
            ],
            [
                'field' => 'setup_contact_mail',
                'type' => 'number',
                'show_name' => '裝機聯絡人Mail-1',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 44
            ],
            [
                'field' => 'setup_contact_mail_1',
                'type' => 'number',
                'show_name' => '裝機聯絡人Mail-2',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 45
            ],
            [
                'field' => 'note',
                'type' => 'text',
                'show_name' => '備註說明',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 46
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 47,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>0],
                    ['text'=>'停用', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'create_user_id',
                'type' => 'select',
                'show_name' => '建立人員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 48,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'update_user_id',
                'type' => 'select',
                'show_name' => '修改人員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 49,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class, 'identity_id');
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'organization_type_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
