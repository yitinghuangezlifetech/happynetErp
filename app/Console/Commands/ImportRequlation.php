<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\MainAttribute;
use App\Models\SubAttribute;
use App\Models\Regulation;
use App\Models\SystemType;

use App\Imports\ImportRequlations;

class ImportRequlation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:regulation {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '匯入所有稽核條文';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = app(SystemType::class)->where('name', '資安系統')->first();

        app(MainAttribute::class)->where('system_type_id', $type->id)->forceDelete();
        app(SubAttribute::class)->where('system_type_id', $type->id)->forceDelete();
        app(Regulation::class)->where('system_type_id', $type->id)->forceDelete();

        $filePath = storage_path('app/public').'/excels/'.$this->argument('file');
        Excel::import(new ImportRequlations, $filePath);

        echo '匯入完成';
    }
}
