<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\FieldAttribute;
use App\Models\SpeedApplySet;
use App\Models\SpeedApplyContent;
use App\Models\SpeedApplyContentItem;
use App\Models\SpeedApplyItemChild;
use App\Models\SpeedApplyChildrenItem;

class SpeedApplySetController extends BasicController
{
    public function create(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $types = app(FieldAttribute::class)->get();

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact('types'));
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $validator = $this->createRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', 'field');
            $formData['id'] = uniqid();
            $fields = $request->field;

            if ($this->model->checkColumnExist('create_user_id'))
            {
                $formData['create_user_id'] = Auth::user()->id;
            }

            if ($this->menu->menuDetails->count() > 0)
            {
                foreach ($this->menu->menuDetails as $detail)
                {
                    if (isset($formData[$detail->field]))
                    {
                        if ($detail->type == 'image' || $detail->type == 'file')
                        {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0)
                            {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $id = $this->model->createData($formData);
                DB::commit();

                $this->proccessFields($id, $fields);
            
                return view('alerts.success', [
                    'msg'=>'資料新增成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料新增失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);

        } 
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = $this->model->find($id);
        $types = app(FieldAttribute::class)->get();

        if (!$data)
        {
            return view('alerts.error',[
                'msg'=>'資料不存在',
                'redirectURL'=>route($this->slug.'.index')
            ]); 
        }

        if(view()->exists($this->slug.'.edit'))
        {
            $this->editView = $this->slug.'.edit';
        }

        return view($this->editView, [
            'data'=>$data,
            'id'=>$id,
            'types'=>$types
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->cannot('update_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method', 'field');
            $fields = $request->field;

            if ($this->model->checkColumnExist('update_user_id'))
            {
                $formData['update_user_id'] = Auth::user()->id;
            }

            if ($this->menu->menuDetails->count() > 0)
            {
                foreach ($this->menu->menuDetails as $detail)
                {
                    if (isset($formData[$detail->field]))
                    {
                        if ($detail->type == 'image' || $detail->type == 'file')
                        {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0)
                            {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $this->model->updateData($id, $formData);

                DB::commit();

                $this->proccessFields($id, $fields);
    
                return view('alerts.success',[
                    'msg'=>'資料更新成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料更新失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        } 
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function preview(Request $request) {
        return view('speed_apply_sets.preview', compact(
            'request'
        ));
    }

    public function multipleDestroy(Request $request)
    {
        if ($request->user()->cannot('delete_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        if (isset($request->ids) && count($request->ids) > 0)
        {
            $this->model->deleteMultipleData($request->ids);

            foreach ($request->ids as $id)
            {
                app(SpeedApplyContent::class)->where('speed_apply_set_id', $id)->delete();
                app(SpeedApplyContentItem::class)->where('speed_apply_set_id', $id)->delete();
                app(SpeedApplyItemChild::class)->where('speed_apply_set_id', $id)->delete();
                app(SpeedApplyChildrenItem::class)->where('speed_apply_set_id', $id)->delete();
            }

            return view('alerts.success',[
                'msg'=>'資料刪除成功',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        return view('alerts.error',[
            'msg'=>'無指定參數',
            'redirectURL'=>route($this->slug.'.index')
        ]);
    }

    private function proccessFields($id, $fields=[])
    {
        if (!empty($fields))
        {
            app(SpeedApplyContent::class)->where('speed_apply_set_id', $id)->delete();

            foreach ($fields as $field)
            {
                $items = [];
                $field['id'] = uniqid();
                $field['speed_apply_set_id'] = $id;

                if (isset($field['items']))
                {
                    $items = $field['items'];
                    unset($field['items']);
                }

                try {
                    $data = app(SpeedApplyContent::class)->create($field);
                }
                catch (\Exception $e) {
                    dd($field);
                }

                if (!empty($items))
                {
                    $this->proccessItems($id, $data->id, $items);
                }
            }
        }
    }

    private function proccessItems($mainId, $contentId, $items=[])
    {
        app(SpeedApplyContentItem::class)
            ->where('speed_apply_set_id', $mainId)
            ->where('speed_apply_content_id', $contentId)
            ->delete();

        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $items = [];
                $item['id'] = uniqid();
                $item['speed_apply_set_id'] = $mainId;
                $item['speed_apply_content_id'] = $contentId;

                if (isset($item['child']))
                {
                    $items = $item['child'];
                    unset($item['child']);
                }

                $data = app(SpeedApplyContentItem::class)->create($item);

                if (!empty($items))
                {
                    $this->proccessItemChildren($mainId, $contentId, $data->id, $items);
                }
            }
        }
    }

    private function proccessItemChildren($mainId, $contentId, $itemId, $childs=[])
    {
        app(SpeedApplyItemChild::class)
            ->where('speed_apply_set_id', $mainId)
            ->where('speed_apply_content_id', $contentId)
            ->where('speed_apply_item_id', $itemId)
            ->delete();

        if (!empty($childs))
        {
            foreach ($childs as $child)
            {
                $items = [];
                $child['id'] = uniqid();
                $child['speed_apply_set_id'] = $mainId;
                $child['speed_apply_content_id'] = $contentId;
                $child['speed_apply_item_id'] = $itemId;

                if (isset($child['items']))
                {
                    $items = $child['items'];
                    unset($child['items']);
                }

                $data = app(SpeedApplyItemChild::class)->create($child);

                if (!empty($items))
                {
                    $this->proccessChildItmes($mainId, $contentId, $itemId, $data->id, $items);
                }
            }
        }
    }

    private function proccessChildItmes($mainId, $contentId, $itemId, $childId, $items=[])
    {
        app(SpeedApplyChildrenItem::class)
            ->where('speed_apply_set_id', $mainId)
            ->where('speed_apply_content_id', $contentId)
            ->where('speed_apply_item_id', $itemId)
            ->delete();

        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $item['id'] = uniqid();
                $item['speed_apply_set_id'] = $mainId;
                $item['speed_apply_content_id'] = $contentId;
                $item['speed_apply_item_id'] = $itemId;
                $item['speed_apply_item_children_id'] = $childId;

                app(SpeedApplyChildrenItem::class)->create($item);
            }
        }
    }
}
