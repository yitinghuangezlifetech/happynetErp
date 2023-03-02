<?php

namespace Database\Seeders;

use DB;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        //app(Permission::class)->truncate();

        foreach ($this->getData() as $data) {
            app(Permission::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData()
    {
        $arr = [];
        $actions = ['browse', 'create', 'edit', 'update', 'delete'];

        $menus = app(Menu::class)
            ->whereNull('parent_id')
            ->orderBy('sort', 'ASC')
            ->get();

        if ($menus->count() > 0)
        {
            foreach ($menus as $menu)
            {
                $childs = $menu->getAllChilds;
                if ($childs->count() > 0)
                {
                    foreach ($childs as $child)
                    {
                        foreach ($actions as $action)
                        {
                            $logs = app(Permission::class)->where('menu_id', $child->id)->get();

                            if ($logs->count() == 0) {
                                array_push($arr, [
                                    'id' => uniqid(),
                                    'menu_id' => $child->id,
                                    'code' => $action.'_'.$child->slug
                                ]);
                            }
                        }
                    }
                }
                else
                {
                    if (!empty($menu->slug))
                    {
                        foreach ($actions as $action)
                        {
                            $logs = app(Permission::class)->where('menu_id', $menu->id)->get();

                            if ($logs->count() == 0) {
                                array_push($arr, [
                                    'id' => uniqid(),
                                    'menu_id' => $menu->id,
                                    'code' => $action.'_'.$menu->slug
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return $arr;
    }
}
