<?php

namespace Database\Seeders;

use DB;
use Storage;
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

        if (Storage::disk('public')->exists('tableData/menus.sql'))
        {
            DB::unprepared(Storage::disk('public')->get('tableData/menus.sql'));
        }
        else
        {
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
                'id' => 3,
                'menu_name' => '組織管理',
                'name' => '組織',
                'icon_class' => 'nav-icon fa fa-store',
                'sort' => 3
            ],
            [
                'id' => uniqid(),
                'menu_name' => '身份設定',
                'name' => '身份',
                'slug' => 'identities',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Identity',
                'parent_id' => 3,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '組織類型設定',
                'name' => '組織類型',
                'slug' => 'store_industries',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\OrganizationType',
                'parent_id' => 3,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '組織設定',
                'name' => '組織',
                'slug' => 'organizations',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\Organization',
                'controller' => 'App\Http\Controllers\OrganizationController',
                'search_component' => 1,
                'parent_id' => 3,
                'sort' => 2
            ],
            [
                'id' => uniqid(),
                'menu_name' => '帳戶類別設定',
                'name' => '帳戶類別',
                'slug' => 'user_types',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\UserType',
                'controller'=>NULL,
                'search_component' => 2,
                'parent_id' => 3,
                'sort' => 3
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
                'parent_id' => 3,
                'sort' => 4
            ],
            [
                'id' => uniqid(),
                'menu_name' => '代理帳戶設定',
                'name' => '代理帳戶',
                'slug' => 'proxy_accounts',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\proxyAccount',
                'controller'=>'App\Http\Controllers\proxyAccountController',
                'search_component' => 1,
                'parent_id' => 3,
                'sort' => 5
            ],
            [
                'id' => 4,
                'menu_name' => '費率管理',
                'name' => '費率',
                'icon_class' => 'nav-icon fas fa-chart-line',
                'sort' => 4
            ],
            [
                'id' => uniqid(),
                'menu_name' => '費率類別設定',
                'name' => '費率類別',
                'slug' => 'fee_rate_types',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\FeeRateType',
                'search_component' => 2,
                'parent_id' => 4,
                'sort' => 0
            ],
            [
                'id' => uniqid(),
                'menu_name' => '服務類別設定',
                'name' => '服務類別',
                'slug' => 'serivce_types',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\ServiceType',
                'search_component' => 2,
                'parent_id' => 4,
                'sort' => 1
            ],
            [
                'id' => uniqid(),
                'menu_name' => '費率IP設定',
                'name' => '費率IP',
                'slug' => 'fee_rate_rules',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\FeeRateRule',
                'search_component' => 2,
                'parent_id' => 4,
                'sort' => 2
            ],
            [
                'id' => uniqid(),
                'menu_name' => '費率設定',
                'name' => '費率',
                'slug' => 'fee_rates',
                'target' => '_self',
                'icon_class' => 'far fa-circle nav-icon',
                'model' => 'App\Models\FeeRate',
                'search_component' => 1,
                'parent_id' => 4,
                'sort' => 3
            ],
        ];
    }
}
