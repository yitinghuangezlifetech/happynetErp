<?php

namespace Database\Seeders;

use DB;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Menu::class)->truncate();

        $id = '';
        $uuid = '';

        foreach ($this->getData() as $data)
        {
            $menu = app(Menu::class)->where('menu_name', $data['menu_name'])->first();

            if ($menu)
            {
                if (!isset($data['parent_id']))
                {
                    $id = $data['id'];
                    $parentId = $menu->id;
                }
                else
                {
                    $parentId = null;
                }
                unset($data['id']);
                app(Menu::class)->where('id', $menu->id)->update($data);
            } 
            else 
            {
                if (isset($parentId)) 
                {
                    if($data['parent_id'] == $id)
                    {
                        $data['parent_id'] = $parentId;
                    }
                }
                else
                {
                    if (!isset($data['parent_id']))
                    {
                        $id = $data['id'];
                        $uuid = uniqid();
                        $data['id'] = $uuid;
                    }
                    else if($data['parent_id'] == $id)
                    {
                        $data['parent_id'] = $uuid;
                    }
                }

                app(Menu::class)->create($data);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData()
    {
        return [
            [
                'id' => 1,
                'menu_name' => '系統管理',
                'name' => '系統',
                'icon_class' => 'nav-icon fa fa-cog',
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '目錄設定',
                'name' => '目錄',
                'slug' => 'menus',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Menu',
                'controller'=>'App\Http\Controllers\MenuController',
                'parent_id' => 1,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '系統類別設定',
                'name' => '系統類別',
                'slug' => 'system_types',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\SystemType',
                'controller'=>'App\Http\Controllers\SystemTypeController',
                'parent_id' => 1,
                'sort' => 1
            ],
            [
                'id' => 2,
                'menu_name' => '權限管理',
                'name' => '權限',
                'icon_class' => 'nav-icon fa fa-id-card',
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '群組設定',
                'name' => '群組',
                'slug' => 'groups',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Group',
                'controller'=>'App\Http\Controllers\GroupController',
                'search_component' => 1,
                'parent_id' => 2,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '角色設定',
                'name' => '角色',
                'slug' => 'roles',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Role',
                'controller'=>'App\Http\Controllers\RoleController',
                'search_component' => 1,
                'parent_id' => 2,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '帳戶設定',
                'name' => '帳戶',
                'slug' => 'users',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\User',
                'controller'=>'App\Http\Controllers\UserController',
                'search_component' => 1,
                'parent_id' => 2,
                'sort' => 2
            ],
            [
                'id' => 3,
                'menu_name' => '商家管理',
                'name' => '商家',
                'icon_class' => 'nav-icon fa fa-store',
                'sort' => 3
            ],
            [
                'id' => uniqid(),
                'menu_name' => '類型設定',
                'name' => '類型',
                'slug' => 'store_types',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\StoreType',
                'parent_id' => 3,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '業別設定',
                'name' => '業別',
                'slug' => 'store_industries',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\StoreIndustry',
                'parent_id' => 3,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '商家設定',
                'name' => '商家',
                'slug' => 'stores',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Store',
                'search_component' => 1,
                'parent_id' => 3,
                'sort' => 2
            ],
            [
                'id' => 4,
                'menu_name' => '條文管理',
                'name' => '條文',
                'icon_class' => 'nav-icon fa fa-book',
                'sort' => 4
            ],
            [
                'id' => uniqid(),
                'menu_name' => '版本設定',
                'name' => '版本',
                'slug' => 'regulation_versions',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\RegulationVersion',
                'parent_id' => 4,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '稽核類別設定',
                'name' => '稽核類別',
                'slug' => 'audit_types',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\AuditType',
                'parent_id' => 4,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '屬性設定',
                'name' => '屬性',
                'slug' => 'main_attributes',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\MainAttribute',
                'parent_id' => 4,
                'sort' => 2
            ],
            [
                'id' => uniqid(),
                'menu_name' => '次屬性設定',
                'name' => '次屬性',
                'slug' => 'sub_attributes',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\SubAttribute',
                'search_component' => 1,
                'parent_id' => 4,
                'sort' => 3
            ],
            [
                'id' => uniqid(),
                'menu_name' => '條文設定',
                'name' => '條文',
                'slug' => 'regulations',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Regulation',
                'controller'=>'App\Http\Controllers\RegulationController',
                'search_component' => 1,
                'import_data' => 1,
                'import_data_route' => 'regulations.importData',
                'parent_id' => 4,
                'sort' => 4
            ],
            [
                'id' => 5,
                'menu_name' => '行程管理',
                'name' => '行程',
                'icon_class' => 'nav-icon fa fa-calendar',
                'sort' => 5
            ],
            [
                'id' => uniqid(),
                'menu_name' => '稽核行程設定',
                'name' => '稽核行程',
                'slug' => 'audit_routes',
                'model' => 'App\Models\AuditRoute',
                'controller'=>'App\Http\Controllers\AuditRouteController',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 5,
                'sort' => 0
            ],
            [
                'id' => 6,
                'menu_name' => '報表管理',
                'name' => '報表',
                'icon_class' => 'nav-icon fas fa-chart-line',
                'sort' => 6
            ],
            [
                'id' => uniqid(),
                'menu_name' => '稽核行程報表',
                'name' => '稽核行程',
                'slug' => 'reports?data=AuditRoute',
                'controller'=>'App\Http\Controllers\ReportController',
                'model' => 'App\Models\AuditRoute',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 6,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '缺失報告報表',
                'name' => '缺失報告',
                'slug' => 'reports?data=Fail',
                'controller'=>'App\Http\Controllers\ReportController',
                'model' => 'App\Models\AuditRoute',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 67,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '餐飲業者評核紀錄報表',
                'name' => '餐飲業者評核紀錄',
                'slug' => 'reports?data=StoreAuditLog',
                'controller'=>'App\Http\Controllers\ReportController',
                'model' => 'App\Models\AuditRoute',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 6,
                'sort' => 2
            ],
            [
                'id' => uniqid(),
                'menu_name' => '評核計畫系統數據報表',
                'name' => '評核計畫系統數據',
                'slug' => 'reports?data=AuditData',
                'controller'=>'App\Http\Controllers\ReportController',
                'model' => 'App\Models\AuditRoute',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 6,
                'sort' => 3
            ],
            [
                'id' => uniqid(),
                'menu_name' => '商家資料報表',
                'name' => '商家資料',
                'slug' => 'reports?data=Store',
                'controller'=>'App\Http\Controllers\ReportController',
                'model' => 'App\Models\Store',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 6,
                'sort' => 4
            ],
            [
                'id' => 7,
                'menu_name' => '問卷活動管理',
                'name' => '問卷活動',
                'icon_class' => 'nav-icon far fa-newspaper',
                'sort' => 7
            ],
            [
                'id' => uniqid(),
                'menu_name' => '題目屬性設定',
                'name' => '題目屬性',
                'slug' => 'topic_types',
                'model' => 'App\Models\TopicType',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 7,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '問卷活動設定',
                'name' => '問卷活動',
                'slug' => 'questionnaire_events',
                'model' => 'App\Models\QuestionnaireEvent',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 7,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '問卷設定',
                'name' => '問卷',
                'slug' => 'questionnaires',
                'controller'=>'App\Http\Controllers\QuestionnaireController',
                'model' => 'App\Models\Questionnaire',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'parent_id' => 7,
                'sort' => 2
            ],
        ];
    }
}
