<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\AuditRecord;
use App\Models\AuditRecordFail;
use App\Models\PendingCheck;
use App\Models\PendingCheckLog;
use App\Models\AuditFailType;

use App\Http\Controllers\Traits\FileServiceTrait as fileService;

class StatusController extends BasicController
{
    use fileService;

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'field' => 'required',
            'model' => 'required',
            'status' => 'required',
            'slug' => 'required',
        ],[
            'id.required' => '缺少id參數',
            'field.required' => '缺少欄位名稱',
            'model.required' => '缺少Model參數',
            'status.required' => '缺少狀態參數',
            'slug.required' => '缺少Slug參數',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        $data = app($request->model)->find($request->id);

        if ($data)
        {
            $data->{$request->field} = $request->status;
            $data->save();

            if ($request->slug == 'project_products')
            {
                if ($request->status == 2)
                {
                    $data->invalid_date = date('Y-m-d H:i:s');
                    $data->save();
                }
                else
                {
                    $data->invalid_date = NULL;
                    $data->save();
                }
            }

            return response()->json([
                'status' => true,
                'message' => '狀態更新成功',
                'data' => null
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '無此資料',
            'data' => null
        ], 404);
    }

    public function changePendingCheckLogStatus(Request $request) 
    {
        $log = app(PendingCheckLog::class)->find($request->id);

        if ($log)
        {
            $log->status = $request->status;
            $log->save();

            return response()->json([
                'status' => true,
                'message' => '狀態更新成功',
                'data' => null
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '無此紀錄存在',
            'data' => null
        ], 404);
    }

    public function changePendingCheckStatus(Request $request)
    {
        $log = app(PendingCheck::class)->find($request->id);

        if ($log)
        {
            $log->status = $request->status;
            $log->save();

            $record = app(AuditRecord::class)
                ->where('audit_route_id', $log->audit_route_id)
                ->where('regulation_id', $log->regulation_id)
                ->first();
            if ($record)
            {
                
                if ($request->status == 2)
                {
                    $regulation = $log->regulation;
                    if ($regulation->is_import == 1) {
                        $failType = app(AuditFailType::class)->where('name', '主要缺失(重大)')->first();
                        $record->audit_fail_type_id = $failType->id;
                    }
                    else if ($regulation->is_main == 1) {
                        $failType = app(AuditFailType::class)->where('name', '主要缺失')->first();
                        $record->audit_fail_type_id = $failType->id;
                    } else {
                        $failType = app(AuditFailType::class)->where('name', '次要缺失')->first();
                        $record->audit_fail_type_id = $failType->id;
                    }
                }

                if ($request->status == 1) {
                    $fails = app(AuditRecordFail::class)
                        ->where('audit_route_id', $log->audit_route_id)
                        ->where('audit_record_id', $record->id)
                        ->get();

                    if ($fails->count() > 0) {
                        foreach ($fails as $fail) {
                            $this->deleteFile( $fail->img );
                            $fail->delete();
                        }
                    }

                    $record->audit_fail_type_id = NULL;
                }

                $record->status = $request->status;
                $record->save();
            }

            return response()->json([
                'status' => true,
                'message' => '狀態更新成功',
                'data' => null
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '無此紀錄存在',
            'data' => null
        ], 404);
    }
}
