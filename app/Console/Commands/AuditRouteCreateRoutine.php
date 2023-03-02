<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AuditRouteRoutine;

use App\Models\AuditRoute;
use App\Http\Controllers\AuditRouteController;

class AuditRouteCreateRoutine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:auditRoute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動檢查是否有列入排程的稽核行程, 並判斷是否要建立一筆相同的資料';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AuditRouteController $controller)
    {
        //AuditRouteRoutine::dispatch($model);

        $routes = app(AuditRoute::class)->where('enable_routine', 1)->orderBy('created_at', 'ASC')->get();

        foreach ($routes??[] as $data)
        {
            $now = date('Y-m-d');
            $week = date('w', strtotime($data->audit_day));
            $day = date('d', strtotime($data->audit_day));
            $monthDay = date('m-d', strtotime($data->audit_day));

            switch ($data->routine_option)
            {
                case 1:
                    $nowWeek = date('w', strtotime($now));
                    if ($nowWeek == $week) {
                        $this->createNewAudiroute($data, $now, $controller);
                    }
                    break;
                case 2:
                    $nowDay = date('d', strtotime($now));
                    if ($nowDay == $day) {
                        $this->createNewAudiroute($data, $now, $controller);
                    }
                    break;
                case 3:
                    $nowMonthDay = date('m-d', strtotime($now));
                    if ($nowMonthDay == $monthDay) {
                        $this->createNewAudiroute($data, $now, $controller);
                    }
                    break;
            }
        }
    }

    private function createNewAudiroute($data, $now, AuditRouteController $controller)
    {
        $log = app(AuditRoute::class)->getLastStatusByStoreId($data->store_id);

        if ($log) {
            if ($log->audit_status == 1) {
                $auditStatus = 2;
            } else {
                $auditStatus = 0;
            }
        }

        $route = app(AuditRoute::class)->create([
            'id' => uniqid(),
            'system_type_id' => $data->system_type_id,
            'main_user_id' => $data->main_user_id,
            'sub_user_id' => $data->sub_user_id,
            'store_id' => $data->store_id,
            'audit_day' => $now,
            'audit_status' => 0,
            'status' => 0,
        ]);

        $controller->createAuditRecords($route, $log);
    }
}
