<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Support\Collection;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Contracts\InterfaceController;

use App\Http\Controllers\Traits\FileServiceTrait as fileService;

class BasicController extends Controller implements InterfaceController
{

    use fileService;

    protected $route;
    protected $slug;
    protected $model;
    protected $menu;
    protected $user;
    protected $indexView = 'templates.index';
    protected $createView = 'templates.create';
    protected $editView = 'templates.edit';
    protected $showView = 'templates.show';

    public $breadcrumbs = [];

    public function __construct(Request $request)
    {
        if (!empty(Route::currentRouteName())) {
            $this->route = explode('.', Route::currentRouteName());

            if (count($this->route) > 2) {
                $this->slug = $this->route[1];
            } else {
                $this->slug = $this->route[0];
            }

            if (isset($this->route[0]) || isset($this->route[1])) {
                $slug = $this->slug;

                $this->menu = app(Menu::class)->getMenuBySlug($slug);

                if (!$this->menu) {
                    return view('alerts.error', [
                        'msg' => '無此功能項目, 請洽系統管理員',
                        'redirectURL' => route('dashboard')
                    ]);
                }

                $this->model = app($this->menu->model);

                $this->setBreadcrumb();

                view()->share([
                    'menu' => $this->menu,
                    'slug' => $this->slug
                ]);
            }
        }
    }

    public function index(Request $request)
    {
        if ($request->user()->cannot('browse_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $filters = $this->getFilters($request);

        try {
            $list = $this->model->getListByFilters($this->menu->menuDetails, $filters);
        } catch (\Exception $e) {
            $list = (new Collection([]))->paginate(20);
        }

        if (view()->exists($this->slug . '.index')) {
            $this->indexView = $this->slug . '.index';
        } else {
            if ($this->menu->sortable_enable == 1) {
                $this->indexView = 'templates.sortable';
            } else {
                $this->indexView = 'templates.index';
            }
        }

        return view($this->indexView, [
            'filters' => $filters,
            'list' => $list
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

        if (view()->exists($this->slug . '.create')) {
            $this->createView = $this->slug . '.create';
        }

        return view($this->createView);
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

        if (view()->exists($this->slug . '.edit')) {
            $this->editView = $this->slug . '.edit';
        }

        return view($this->editView, [
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

    public function show(Request $request, $id)
    {
        if ($request->user()->cannot('show_' . $this->slug,  $this->model)) {
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

        if (view()->exists($this->slug . '.show')) {
            $this->showView = $this->slug . '.show';
        }

        return view($this->showView, [
            'data' => $data,
            'id' => $id
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->cannot('delete_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        try {
            $data = $this->model->find($id);

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if ($detail->type == 'image' || $detail->type == 'file') {
                        $this->deleteFile($data->{$detail->field});
                    }
                }
            }

            $this->model->deleteData($id);

            return view('alerts.success', [
                'msg' => '資料刪除成功',
                'redirectURL' => route($this->slug . '.index')
            ]);
        } catch (\Exception $e) {
            return view('alerts.error', [
                'msg' => $e->getMessage(),
                'redirectURL' => route($this->slug . '.index')
            ]);
        }
    }

    public function multipleDestroy(Request $request)
    {
        if ($request->user()->cannot('delete_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        if (isset($request->ids) && count($request->ids) > 0) {
            $this->model->deleteMultipleData($request->ids);

            return view('alerts.success', [
                'msg' => '資料刪除成功',
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        return view('alerts.error', [
            'msg' => '無指定參數',
            'redirectURL' => route($this->slug . '.index')
        ]);
    }

    public function importData(Request $request)
    {
        $str = explode('_', $this->slug);

        if (count($str) > 1) {
            if (class_exists('App\Imports\\' . ucfirst($str[0]) . ucfirst($str[1]) . 'Import')) {
                $import = app('App\Imports\\' . ucfirst($str[0]) . ucfirst($str[1]) . 'Import');
            }
        } else {
            if (class_exists('App\Imports\\' . ucfirst($str[0]) . 'Import')) {
                $import = app('App\Imports\\' . ucfirst($str[0]) . 'Import');
            }
        }

        Excel::import($import, $request->file->store($this->slug));

        return view('admins.alerts.success', [
            'msg' => '資料匯入成功',
            'redirectURL' => route('admin.' . $this->slug . '.index')
        ]);
    }

    public function createRule($formData)
    {
        $rules = [];
        $messages = [];
        $validator = [];

        if ($this->menu->menuRequiredDetails->count() > 0) {
            foreach ($this->menu->menuRequiredDetails as $detail) {
                if (!empty($detail->create_rule)) {
                    $errors = json_decode($detail->error_msg, true);
                    $rules  = array_merge($rules, json_decode($detail->create_rule, true));

                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            $messages = array_merge($messages, $error);
                        }
                    }
                }
            }
        }

        if (!empty($rules)) {
            $validator = Validator::make($formData, $rules, $messages);
            return $validator;
        }

        return $validator;
    }

    public function updateRule($formData)
    {
        $rules = [];
        $messages = [];
        $validator = [];

        if ($this->menu->menuRequiredDetails->count() > 0) {
            foreach ($this->menu->menuRequiredDetails as $detail) {
                if (!empty($detail->update_rule)) {
                    $errors = json_decode($detail->error_msg, true);
                    $rules  = array_merge($rules, json_decode($detail->update_rule, true));

                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            $messages = array_merge($messages, $error);
                        }
                    }
                }
            }
        }

        if (!empty($rules)) {
            $validator = Validator::make($formData, $rules, $messages);
            return $validator;
        }

        return $validator;
    }

    public function getFilters(Request $request)
    {
        $filters = [];
        $filters['rows'] = $request->rows ?? 20;

        if (!empty($this->menu) && $this->menu->search_component == 1) {
            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if ($detail->join_search == 1) {

                        if ($detail->type == 'date_time') {
                            if (isset($request->start_day) && isset($request->end_day)) {
                                if (!empty($request->start_day) && !empty($request->end_day)) {
                                    $filters['start_day'] = $request->start_day . ' 00:00:00';
                                    $filters['end_day'] = $request->end_day . ' 23:59:59';
                                }
                            }
                        } else if ($detail->type == 'date') {
                            if (isset($request->start_day) && isset($request->end_day)) {
                                if (!empty($request->start_day) && !empty($request->end_day)) {
                                    $filters['start_day'] = $request->start_day;
                                    $filters['end_day'] = $request->end_day;
                                }
                            } else {
                                $filters['start_day'] = null;
                                $filters['end_day'] = null;
                            }
                        } else if ($detail->type == 'multiple_select') {
                            $filters[$detail->field_id] = $request->{$detail->field_id};
                        } else {
                            $filters[$detail->field] = $request->{$detail->field};
                        }
                    }
                }
            }
        }

        return $filters;
    }

    public function setBreadcrumb()
    {
        if (!empty($this->menu->getParent)) {
            $parentName = $this->menu->getParent->menu_name;

            try {
                $this->breadcrumbs = [
                    'mainMenu' => $parentName,
                    'routeName' => $this->slug,
                    'breadcrumb' => [
                        ['name' => $parentName, 'active' => true, 'breadUrl' => false],
                        ['name' => $this->menu->menu_name, 'active' => false, 'breadUrl' => route($this->slug . '.index')],
                        ['name' => $this->route[2] ?? '', 'active' => true, 'breadUrl' => false],
                    ]
                ];
            } catch (\Exception $e) {
            }
        } else {
            $parentName = $this->menu->menu_name;

            try {
                $this->breadcrumbs = [
                    'mainMenu' => $parentName,
                    'routeName' => $this->slug,
                    'breadcrumb' => [
                        ['name' => $parentName, 'active' => true, 'breadUrl' => false],
                        ['name' => $this->route[2] ?? '', 'active' => true, 'breadUrl' => false],
                    ]
                ];
            } catch (\Exception $e) {
            }
        }

        view()->share($this->breadcrumbs);
    }
}
