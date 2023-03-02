<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditRecord;
use App\Models\AuditRecordItem;
use App\Models\AuditRecordFail;
use App\Models\PendingCheck;
use App\Models\PendingCheckLog;
use App\Models\PendingCheckItem;
use App\Models\PendingCheckResult;
use App\Services\Files\FileServiceInterface;

class PendingCheckController extends BasicController {
    
    public function fail($checkId, $logId) {
        $items = [];
        $record = app(PendingCheck::class)->find($checkId);
        $checkLog = app(PendingCheckLog::class)->find($logId);

        if ($record->regulation->items->count() > 0) {
            if ($record->items->count() > 0) {
                foreach($record->items as $item) {
                    $items[$item->regulation_item_id] = $item->value;
                }
            }
        }

        return view('audit_records.check_fail', compact(
            'record', 'checkLog', 'items'
        ));
    }

    public function success($checkId, $logId) {

        $items = [];
        $record = app(PendingCheck::class)->find($checkId);
        $checkLog = app(PendingCheckLog::class)->find($logId);

        if ($record->regulation->items->count() > 0) {
            if ($record->items->count() > 0) {
                foreach($record->items as $item) {
                    $items[$item->regulation_item_id] = $item->value;
                }
            }
        }

        return view('audit_records.check_success', compact(
            'record', 'checkLog', 'items'
        ));
    }

    public function successRecord(Request $request) {

        $fails = $request->fails??[];
        $items = $request->items??[];

        $pendingCheck = app(PendingCheck::class)->find($request->pending_check_id);

        $record = app(AuditRecord::class)
                ->where('audit_route_id', $pendingCheck->audit_route_id)
                ->where('regulation_id', $pendingCheck->regulation_id)
                ->first();

        if (is_array($fails) && count($fails) > 0) {
            foreach ($fails as $fail) {
                if (isset($fail['id'])) {
                    if (isset($fail['img']) && is_object($fail['img']) && $fail['img']->getSize() > 0) {
                        $fail['img'] = $this->storeFile($fail['img'], 'pending_checks');
                    }
                    app(PendingCheckResult::class)->where('id', $fail['id'])->update($fail);
                } else {

                    $fail['id'] = uniqid();
                    $fail['pending_check_id'] = $request->pending_check_id;
                    $fail['pending_check_log_id'] = $request->pending_check_log_id;
                    $fail['record_type'] = 1;

                    if (isset($fail['img']) && is_object($fail['img']) && $fail['img']->getSize() > 0) {
                        $fail['img'] = $this->storeFile($fail['img'], 'pending_checks');
                    }
                    app(PendingCheckResult::class)->create($fail);
                }
            }

            app(AuditRecord::class)
                ->where('audit_route_id', $pendingCheck->audit_route_id)
                ->where('regulation_id', $pendingCheck->regulation_id)
                ->update([
                    'status' => 1
                ]);
        }

        if (count($items) > 0) {
            foreach($items as $item) {

                $checkItem = app(PendingCheckItem::class)
                    ->where('pending_check_id', $request->pending_check_id)
                    ->where('regulation_item_id', $item['regulation_item_id'])
                    ->first();
                if ($checkItem) {
                    $checkItem->value = $item['value'];
                    $checkItem->save();
                } else {
                    app(PendingCheckItem::class)->create([
                        'id' => uniqid(),
                        'pending_check_id' => $request->pending_check_id,
                        'regulation_item_id' => $item['regulation_item_id'],
                        'value' => $item['value']
                    ]);
                }

                $log = app(AuditRecordItem::class)
                    ->where('audit_record_id', $record->id)
                    ->where('regulation_item_id', $item['regulation_item_id'])
                    ->first();
                if ($log) {
                    $log->value = $item['value'];
                    $log->save();
                } else {
                    app(AuditRecordItem::class)->create([
                        'id' => uniqid(),
                        'audit_record_id' => $record->id,
                        'regulation_item_id' => $item['regulation_item_id'],
                        'value' => $item['value']
                    ]);
                }
            }
        }

        return view('alerts.success', [
            'msg'=>'資料更新成功',
            'redirectURL'=>route('audit_records.getFailItems', ['routeId'=>$pendingCheck->audit_route_id])
        ]);
    }

    public function failRecord(Request $request) {

        $fails = $request->fails??[];
        $items = $request->items??[];

        if (is_array($fails) && count($fails) > 0) {
            $pendingCheck = app(PendingCheck::class)->find($request->pending_check_id);

            $record = app(AuditRecord::class)
                ->where('audit_route_id', $pendingCheck->audit_route_id)
                ->where('regulation_id', $pendingCheck->regulation_id)
                ->first();

            foreach ($fails as $fail) {
                if (isset($fail['id'])) {
                    if (isset($fail['img']) && is_object($fail['img']) && $fail['img']->getSize() > 0) {
                        $fail['img'] = $this->storeFile($fail['img'], 'pending_checks');
                    }
                    app(PendingCheckResult::class)->where('id', $fail['id'])->update($fail);

                    app(AuditRecordFail::class)
                        ->where('audit_route_id', $pendingCheck->audit_route_id)
                        ->where('audit_record_id', $record->id)
                        ->update([
                            'regulation_fact_id' => $fail['regulation_fact_id'],
                            'img' => $fail['img'],
                            'note' => $fail['note']
                        ]);
                } else {

                    $fail['id'] = uniqid();
                    $fail['pending_check_id'] = $request->pending_check_id;
                    $fail['pending_check_log_id'] = $request->pending_check_log_id;
                    $fail['record_type'] = 2;

                    if (isset($fail['img']) && is_object($fail['img']) && $fail['img']->getSize() > 0) {
                        $fail['img'] = $this->storeFile($fail['img'], 'pending_checks');
                    }
                    app(PendingCheckResult::class)->create($fail);

                    if ($record) {

                        app(AuditRecordFail::class)->create([
                            'id' => uniqid(),
                            'audit_route_id' => $pendingCheck->audit_route_id,
                            'audit_record_id' => $record->id,
                            'regulation_fact_id' => $fail['regulation_fact_id'],
                            'img' => $fail['img'],
                            'note' => $fail['note']
                        ]);
                    }
                }
            }

            app(AuditRecord::class)
                ->where('audit_route_id', $pendingCheck->audit_route_id)
                ->where('regulation_id', $pendingCheck->regulation_id)
                ->update([
                    'status' => 2
                ]);
        }

        if (count($items) > 0) {
            foreach($items as $item) {

                $checkItem = app(PendingCheckItem::class)
                    ->where('pending_check_id', $request->pending_check_id)
                    ->where('regulation_item_id', $item['regulation_item_id'])
                    ->first();
                if ($checkItem) {
                    $checkItem->value = $item['value'];
                    $checkItem->save();
                } else {
                    app(PendingCheckItem::class)->create([
                        'id' => uniqid(),
                        'pending_check_id' => $request->pending_check_id,
                        'regulation_item_id' => $item['regulation_item_id'],
                        'value' => $item['value']
                    ]);
                }

                $log = app(AuditRecordItem::class)
                    ->where('audit_record_id', $record->id)
                    ->where('regulation_item_id', $item['regulation_item_id'])
                    ->first();
                if ($log) {
                    $log->value = $item['value'];
                    $log->save();
                } else {
                    app(AuditRecordItem::class)->create([
                        'id' => uniqid(),
                        'audit_record_id' => $record->id,
                        'regulation_item_id' => $item['regulation_item_id'],
                        'value' => $item['value']
                    ]);
                }
            }
        }

        return view('alerts.success', [
            'msg'=>'資料更新成功',
            'redirectURL'=>route('audit_records.getFailItems', ['routeId'=>$pendingCheck->audit_route_id])
        ]);
    }

    public function removeResult(Request $request) {
        $log = app(PendingCheckResult::class)->find($request->id);

        if ($log) {
            $this->deleteFile($log->img);

            $log->delete();
        }
    }

    public function checkRecord(Request $request) {
        $pass = true;
        $check = app(PendingCheck::class)->find($request->id);

        if ($check) {
            if ($check->status == 1) {
                if ($check->pendingCheckLogs->count() > 0) {
                    foreach ($check->pendingCheckLogs as $log) {
                        if ($check->getSuccessLogsByLogId($log->id)->count() == 0) {
                            return response()->json([
                                'status' => false,
                                'message' => '['.$check->regulation->clause.']項目尚有合格資料未填寫',
                                'data' => null
                            ], 400);
                        }
                    }
                }
                return response()->json([
                    'status' => true,
                    'message' => '已填寫紀錄資料',
                    'data' => null
                ], 200);
            }
            if ($check->status == 2) {
                if ($check->pendingCheckLogs->count() > 0) {
                    foreach ($check->pendingCheckLogs as $log) {
                        if ($check->getFailLogsByLogId($log->id)->count() == 0) {
                            return response()->json([
                                'status' => false,
                                'message' => '['.$check->regulation->clause.']項目尚有缺失資料未填寫',
                                'data' => null
                            ], 400);
                        }
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => '已填寫紀錄資料',
                    'data' => null
                ], 200);
            }
        }
    }
}
