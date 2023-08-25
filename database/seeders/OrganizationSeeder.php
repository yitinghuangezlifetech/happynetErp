<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Group;
use App\Models\FuncType;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(Organization::class)->truncate();

        $group = app(Group::class)->where('name', '系統管理')->first();

        $parentIdentity = app(FuncType::class)->where('type_code', 'identity_types')->first();
        $parentorganizationType = app(FuncType::class)->where('type_code', 'org_types')->first();

        if ($parentIdentity) {
            $identity = app(FuncType::class)->where('parent_id', $parentIdentity->id)->where('type_name', '系統管理')->first();

            if ($identity) {
                if ($parentorganizationType) {
                    $organizationType = app(FuncType::class)->where('parent_id', $parentorganizationType->id)->where('type_name', '企業')->first();
                    $organization = app(Organization::class)->create([
                        'id' => uniqid(),
                        'identity_id' => $identity->id,
                        'organization_type_id' => $organizationType->id,
                        'group_id' => $group->id,
                        'system_no' => 'HN070701',
                        'name' => '樂得網路電信科技有限公司',
                        'id_no' => '97262802',
                        'manager' => '陳永濬',
                        'manager_id_no' => 'AB123567894',
                        'mobile' => '0988123456',
                        'tel' => '02-26623319',
                        'county' => '新北市',
                        'district' => '深坑區',
                        'zipcode' => '222',
                        'address' => '北深路2段142號(1樓)',
                        'bill_county' => '新北市',
                        'bill_district' => '深坑區',
                        'bill_zipcode' => '222',
                        'bill_address' => '北深路2段142號(1樓)',
                        'business_county' => '新北市',
                        'business_district' => '深坑區',
                        'business_zipcode' => '222',
                        'business_address' => '北深路2段142號(1樓)',
                        'email' => 'service@happynet.cc',
                        'official_website' => 'https://www.happynet.cc/',
                        'expiry_day' => '2030-12-31',
                        'bill_day' => '5',
                        'bill_contact' => '王小明',
                        'bill_contact_mobile' => '0987123456',
                        'bill_contact_tel' => '02-26623319',
                        'bill_contact_mail' => 'wangxaimin@happynet.cc',
                        'setup_contact' => '桃樂比',
                        'setup_contact_mobile' => '0928456123',
                        'setup_contact_tel' => '02-26623319',
                        'setup_contact_mail' => 'taobi@happynet.cc',
                        'status' => 1,
                    ]);

                    app(Organization::class)->factory()->count(30)->create()->each(function ($data, $i) use ($organization) {

                        $rows = $i + 1;

                        if ($rows == 1 || $rows % 4 == 0) {
                            $parentId = $organization->id;
                            session(['parent_id' => $data->id]);
                        } else {
                            $parentId = session('parent_id');
                        }

                        $types  = app(FuncType::class)->where('type_code', 'org_type')->first();

                        $i = rand(0, 1);

                        foreach ($types->getChilds ?? [] as $k => $info) {
                            if ($i == $k) {
                                $type = $info;
                            }
                        }

                        $group = app(Group::class)->where('name', '!=', '系統管理')->inRandomOrder()->first();
                        $type = app(FuncType::class)->where('type_code', 'identity_types')->first();
                        $identity = $type->getChildNoFilter;

                        $data->organization_type_id = $type->id;
                        $data->identity_id = $identity[rand(0, 2)]->id;
                        $data->group_id = $group->id;
                        $data->parent_id = $parentId;
                        $data->save();
                    });
                }
            }
        }
    }
}
