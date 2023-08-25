<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Support\Collection;

use App\Models\RateTypeIp;


class FuncTypeController extends BasicController
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('browse_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $filters = $this->getFilters($request);
        $filters['type_code'] = ($request->type_code != $this->slug) ? $request->type_code : NULL;


        $list = (new Collection([]))->paginate($filters['rows'] ?? 20);
        $data = $this->model->getDataByTypeCode($this->slug);

        if ($data) {
            $list = $data->getChilds($filters)->paginate($filters['rows'] ?? 20);
        } else {
            $list = $this->model->getListByFilters($filters);
        }

        $this->indexView = 'func_type.sortable';

        return view($this->indexView, [
            'filters' => $filters,
            'parent_id' => $data->id ?? null,
            'list' => $list,
            'slug' => $this->slug
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        return view('func_type.create', [
            'parent_id' => $request->parent_id,
            'slug' => $this->slug
        ]);
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $validator = $this->createRule($request->all());

        if (!is_array($validator) && $validator->fails()) {
            return view('alerts.error', [
                'msg' => $validator->errors()->all()[0],
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token');
            $formData['id'] = uniqid();
            $ips = $request->ip ?? [];

            if ($this->model->checkColumnExist('create_user_id')) {
                $formData['create_user_id'] = Auth::user()->id;
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

                $id = $this->model->createData($formData);
                DB::commit();

                if (!empty($ips)) {
                    $this->proccessIp($id, $ips);
                }

                return view('alerts.success', [
                    'msg' => '資料新增成功',
                    'redirectURL' => route($this->slug . '.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg' => '資料新增失敗, 無該功能項之細項設定',
                'redirectURL' => route($this->slug . '.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error', [
                'msg' => $e->getMessage(),
                'redirectURL' => route($this->slug . '.index')
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = $this->model->find($id);

        if (!$data) {
            return view('alerts.error', [
                'msg' => '資料不存在',
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        return view('func_type.edit', [
            'data' => $data,
            'id' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->cannot('update_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails()) {
            return view('alerts.error', [
                'msg' => $validator->errors()->all()[0],
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method');
            $ips = $request->ip ?? [];

            if ($this->model->checkColumnExist('update_user_id')) {
                $formData['update_user_id'] = Auth::user()->id;
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

                if (!empty($ips)) {
                    $this->proccessIp($id, $ips);
                }

                DB::commit();

                return view('alerts.success', [
                    'msg' => '資料更新成功',
                    'redirectURL' => route($this->slug . '.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg' => '資料更新失敗, 無該功能項之細項設定',
                'redirectURL' => route($this->slug . '.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error', [
                'msg' => $e->getMessage(),
                'redirectURL' => route($this->slug . '.index')
            ]);
        }
    }

    private function proccessIp($id, $ips = [])
    {
        if (!empty($ips)) {
            app(RateTypeIp::class)->where('rate_type_id', $id)->delete();

            foreach ($ips as $ip) {
                app(RateTypeIp::class)->create([
                    'id' => uniqid(),
                    'rate_type_id' => $id,
                    'ip' => $ip
                ]);
            }
        }
    }
}
