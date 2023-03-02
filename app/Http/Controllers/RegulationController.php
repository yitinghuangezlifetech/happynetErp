<?php

namespace App\Http\Controllers;

use App\Imports\RegulationImport;

use App\Models\AuditFailType;
use App\Models\MainAttribute;
use App\Models\RegulationItem;
use App\Models\RegulationFact;
use App\Models\RegulationFail;
use App\Models\RegulationVersion;

use DB;
use Auth;
use Excel;
use Illuminate\Http\Request;

class RegulationController extends BasicController {

    public function index(Request $request) 
    {
        if ($request->user()->cannot('browse_'.$this->slug,  $this->model)) 
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        
        $versions = app(RegulationVersion::class)
            ->where('system_type_id', Auth::user()->role->system_type_id)
            ->get();

        $filters = $this->getFilters($request);
        $list    = $this->model->getListByFilters($this->menu->menuDetails, $filters);
        $columns = $this->proccessColums();

        if(view()->exists($this->slug.'.index')) 
        {
            $this->indexView = $this->slug.'.index';
        } 
        else 
        {
            if ($this->menu->sortable_enable == 1) 
            {
                $this->indexView = 'templates.sortable';
            }
            else
            {
                $this->indexView = 'templates.index';
            }
        }

        return view($this->indexView, [
            'filters' => $filters,
            'list' => $list,
            'columns' => $columns,
            'versions' => $versions
        ]);
    }

    public function create(Request $request) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('create_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        if(view()->exists($this->slug.'.create')) {
            $this->createView = $this->slug.'.create';
        }

        $failTypes = app(AuditFailType::class)->get();
        $subAttributes = $this->getSubAttributeOptions();
        $columns = $this->proccessColums();

        return view($this->createView, compact('subAttributes', 'failTypes', 'columns'));
    }

    public function store(Request $request) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('create_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $validator = $this->createRule($request->all());

        if (!is_array($validator) && $validator->fails()){
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $items = [];
            $facts = [];
            $fails = [];
            $formData = $request->except('_token');
            $formData['id'] = uniqid();

            if (isset($formData['items']) && count($formData['items']) > 0) {
                $items = $formData['items'];
                unset($formData['items']);
            }
            if (isset($formData['facts']) && count($formData['facts']) > 0) {
                $facts = $formData['facts'];
                unset($formData['facts']);
            }
            if (isset($formData['fails']) && count($formData['fails']) > 0) {
                $fails = $formData['fails'];
                unset($formData['fails']);
            }

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if (isset($formData[$detail->field])) {
                        if ($detail->type == 'image' || $detail->type == 'file') {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0) {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $data = $this->model->createData($formData);

                $this->proccessItems($data, $items);
                $this->proccessFacts($data, $facts);
                $this->proccessFails($data, $fails);

                DB::commit();
            
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

        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function edit(Request $request, $id) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('edit_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $data = $this->model->editData($id);
        $failTypes = app(AuditFailType::class)->get();
        $subAttributes = $this->getSubAttributeOptions();
        $columns = $this->proccessColums();

        if (!$data) {
            return view('alerts.error',[
                'msg'=>'資料不存在',
                'redirectURL'=>route($this->slug.'.index')
            ]); 
        }

        if(view()->exists($this->slug.'.edit')) {
            $this->editView = $this->slug.'.edit';
        }

        return view($this->editView, [
            'data'=>$data,
            'id'=>$id,
            'subAttributes'=>$subAttributes,
            'failTypes'=>$failTypes,
            'columns'=>$columns
        ]);
    }

    public function update(Request $request, $id) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('update_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }
        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails()){
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $items = [];
            $facts = [];
            $formData = $request->except('_token', '_method');

            if (isset($formData['items']) && count($formData['items']) > 0) {
                $items = $formData['items'];
                unset($formData['items']);
            }
            if (isset($formData['facts']) && count($formData['facts']) > 0) {
                $facts = $formData['facts'];
                unset($formData['facts']);
            }
            if (isset($formData['fails']) && count($formData['fails']) > 0) {
                $fails = $formData['fails'];
                unset($formData['fails']);
            }

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if (isset($formData[$detail->field])) {
                        if ($detail->type == 'image' || $detail->type == 'file') {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0) {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $this->model->updateData($id, $formData);

                $data = $this->model->editData($id);
                $this->proccessItems($data, $items);
                $this->proccessFacts($data, $facts);
                $this->proccessFails($data, $fails);

                DB::commit();
    
                return view('alerts.success',[
                    'msg'=>'資料更新成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料新增失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    private function proccessItems($data, $items) {
        if (!empty($items) && count($items) > 0) {
            app(RegulationItem::class)->where('regulation_id', $data->id)->delete();

            foreach ($items as $item) {
                if (!empty($item)) {
                    app(RegulationItem::class)->create([
                        'id' => uniqid(),
                        'regulation_id' => $data->id,
                        'name' => $item
                    ]);
                }
            }
        } else {
            app(RegulationItem::class)->where('regulation_id', $data->id)->delete();
        }
    }

    public function importData(Request $request)
    {
        $str = explode('_', $this->slug);
        
        if(count($str) > 1)
        {
            if (class_exists('App\Imports\\'.ucfirst($str[0]).ucfirst($str[1]).'Import'))
            {
                $import = app('App\Imports\\'.ucfirst($str[0]).ucfirst($str[1]).'Import');
            }
        }
        else
        {

            if (class_exists('App\Imports\\'.ucfirst($str[0]).'Import'))
            {
                $import = app('App\Imports\\'.ucfirst($str[0]).'Import');
            }
        }

        $import->systemType = Auth::user()->role->system_type_id;
        $import->versionId = $request->regulation_version_id;

        Excel::import($import, $request->file->store($this->slug));

        return view('alerts.success',[
            'msg'=>'資料匯入成功',
            'redirectURL'=>route($this->slug.'.index')
        ]);
    }

    private function proccessFacts($data, $facts) {
        if (!empty($facts) && count($facts) > 0) {
            app(RegulationFact::class)->where('regulation_id', $data->id)->delete();

            foreach ($facts as $fact) {
                if (!empty($fact)) {
                    app(RegulationFact::class)->create([
                        'id' => uniqid(),
                        'regulation_id' => $data->id,
                        'name' => $fact
                    ]);
                }
            }
        } else {
            app(RegulationFact::class)->where('regulation_id', $data->id)->delete();
        }
    }

    private function proccessFails($data, $fails) {
        if (!empty($fails) && count($fails) > 0) {
            app(RegulationFail::class)->where('regulation_id', $data->id)->delete();

            foreach ($fails as $failId) {
                if (!empty($failId)) {
                    app(RegulationFail::class)->create([
                        'id' => uniqid(),
                        'system_type_id' => $data->system_type_id,
                        'regulation_id' => $data->id,
                        'audit_fail_type_id' => $failId
                    ]);
                }
            }
        } else {
            app(RegulationFail::class)->where('regulation_id', $data->id)->delete();
        }
    }

    private function getSubAttributeOptions() {
        $data = [];
        $mainAttributes = app(MainAttribute::class)->get();

        if ($mainAttributes->count() > 0) {
            foreach ($mainAttributes as $attribute) {
                if ($attribute->subAttributes->count() > 0) {
                    foreach ($attribute->subAttributes as $key=>$sub) {
                        $data[$attribute->id][$key]['id'] = $sub->id;
                        $data[$attribute->id][$key]['name'] = $sub->name;
                    }
                }
            }
        }
        return $data;
    }

    private function proccessColums()
    {
        $arr = [];
        $user    = Auth::guard('web')->user();

        if ($user->role) 
        {
            if ($user->role->systemType) 
            {
                if ($user->role->systemType->colums->count() > 0 ) 
                {
                    foreach ($user->role->systemType->colums as $field)
                    {
                        $arr[$field->field] = $field->name;
                    }
                }
            }
        }

        return $arr;
    }
}
