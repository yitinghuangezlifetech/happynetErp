<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(MenuDetail::class)->truncate();

        foreach ($this->getData() as $sort=>$data)
        {
            $data['sort'] = $sort;
            
            app(MenuDetail::class)->create($data);
        }
    }

    private function getData()
    {
        $arr = [];

        $menus = app(Menu::class)->get();
        if ($menus->count() > 0)
        {
            foreach ($menus as $menu)
            {
                if (!empty($menu->model))
                {
                    $model = app($menu->model);
                    $details = $model->getFieldProperties();

                    if (!empty($details) && count($details) > 0)
                    {
                        foreach ($details as $detail)
                        {
                            $detail['id'] = uniqid();
                            $detail['menu_id'] = $menu->id;
                            if (isset($detail['options']))
                            {
                                $detail['options'] = preg_replace('/\s+/', '', $detail['options']); 
                            }

                            if (isset($detail['relationship']))
                            {
                                $detail['relationship'] = preg_replace('/\s+/', '', $detail['relationship']); 
                            }

                            if (isset($detail['attributes']))
                            {
                                $detail['attributes'] = preg_replace('/\s+/', '', $detail['attributes']); 
                            }

                            array_push($arr, $detail);
                        }
                    }
                }
            }
        }
        
        return $arr;
    }
}
