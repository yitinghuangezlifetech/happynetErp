<?php

namespace App\Console\Commands;

use DB;
use Storage;
use Illuminate\Console\Command;

class StorageTableData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:table {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將目前資料表中的資料存成json格式並寫入檔案';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arr = [];
        $logs = DB::select('select * from '.$this->argument('tableName'));

        foreach ($logs??[] as $data)
        {
            array_push($arr, $data);
        }

        if (!Storage::disk('public')->exists('tableData/'.$this->argument('tableName').'.txt'))
        {
            Storage::disk('public')->put('tableData/'.$this->argument('tableName').'.txt', json_encode($arr));
        }
        else
        {
            Storage::disk('public')->delete('tableData/'.$this->argument('tableName').'.txt');
            Storage::disk('public')->put('tableData/'.$this->argument('tableName').'.txt', json_encode($arr));
        }
    }
}
