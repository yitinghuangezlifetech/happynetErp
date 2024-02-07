<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;

use App\Models\Apply;
use App\Models\Product;

use App\Http\Controllers\Traits\FileServiceTrait as fileService;

class RecipientController extends BasicController
{
    use fileService;

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->position) {
            if ($request->user()->cannot('browse_' . $this->slug,  $this->model) || $user->position->type_name != '收件人') {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => route('dashboard')
                ]);
            }
        }

        $filters = [
            'status' => 5
        ];

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

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = app(Apply::class)->find($id);
        $applyLogs = $this->getProductLog($data);

        $obj = app(Product::class);

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
            'id' => $id,
            'applyLogs' => $applyLogs,
            'obj' => $obj,
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

                if (isset($formData['recipient_sign']) && !empty($formData['recipient_sign'])) {
                    $formData['recipient_sign'] = $this->storeBase64($formData['recipient_sign'], 'applies', date('Ymd') . uniqid() . '.svg');
                }

                $this->model->updateData($id, $formData);

                DB::commit();

                return view('alerts.success', [
                    'msg' => '收件成功',
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

    public function getProductLog($apply)
    {
        $arr = [];

        foreach ($apply->productLogs ?? [] as $log) {
            if ($log->qty > 0) {
                $arr[$log->product_type_id][$log->product_id]['qty'] = $log->qty;
                $arr[$log->product_type_id][$log->product_id]['rent_month'] = $log->rent_month;
                $arr[$log->product_type_id][$log->product_id]['discount'] = $log->discount;
                $arr[$log->product_type_id][$log->product_id]['amount'] = $log->amount;
                $arr[$log->product_type_id][$log->product_id]['security_deposit'] = $log->security_deposit;
                $arr[$log->product_type_id][$log->product_id]['note'] = $log->note;
            } else {
                foreach ($log->feeRateLogs ?? [] as $k => $rate) {
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['call_target_id'] = $rate->call_target_id;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['call_rate'] = $rate->call_rate;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['discount'] = $rate->discount;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['amount'] = $rate->amount;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['charge_unit'] = $rate->charge_unit;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['parameter'] = $rate->parameter;
                }
            }
        }

        return $arr;
    }
}
