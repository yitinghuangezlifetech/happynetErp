<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class SortableController extends BasicController
{
    public function sort(Request $request)
    {
        $this->slug =  $request->slug;
        $this->menu = app(Menu::class)->getMenuBySlug($this->slug);
        $this->model = app($this->menu->model);

        $ids = $request->sort;

        if (is_array($ids) && count($ids) > 0)
        {
            foreach ($ids as $key=>$id) {
                $log = $this->model->where('id', $id['value'])->update([
                    'sort' => $key
                ]);
            }
        }
    }

    public function hierarchySort(Request $request)
    {
        $this->slug =  $request->slug;
        $this->menu = app(Menu::class)->getMenuBySlug($this->slug);
        $this->model = app($this->menu->model);

        if (count($request->data) > 0)
        {
            foreach ($request->data as $k1=>$data)
            {
                $parentGroup = $this->model->find($data['id']);

                if ($parentGroup)
                {
                    $parentGroup->parent_id = NULL;
                    $parentGroup->sort = $k1;
                    $parentGroup->save();
                }

                if (isset($data['children']) && count($data['children']) > 0)
                {
                    $this->proccessChild($parentGroup, $data);
                }
            }
        }

        return response()->json([
            'status'=>true,
            'message'=>'更新資料成功',
            'data'=>null
        ], 200);
    }

    private function proccessChild($parentGroup, $data)
    {
        if (isset($data['children']) && count($data['children']) > 0)
        {
            foreach ($data['children'] as $k2=>$child) 
            {
                $group = $this->model->find($child['id']);

                if ($group)
                {
                    $group->parent_id = $data['id'];
                    $group->sort = $k2;
                    $group->save();
                }

                $this->proccessChild($parentGroup, $child);
            }
        }
    }
}
