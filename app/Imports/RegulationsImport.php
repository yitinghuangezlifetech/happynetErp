<?php

namespace App\Imports;

use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\MainAttribute;
use App\Models\SubAttribute;
use App\Models\Regulation;
use App\Models\SystemType;

class RegulationsImport implements ToCollection
{
    public $systemType, $versionId;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $mainSort = 0;
        $subSort  = 0;
        $requlationSort = 0;
        
        foreach ($rows as $key=>$row)
        {
            if ($key > 4)
            {
                if (!empty($row[0]))
                {
                    $main = app(MainAttribute::class)
                        ->where('system_type_id', $this->systemType)
                        ->where('name', $this->removeBlankSpace($row[0]))
                        ->first();
                        
                    if (!$main)
                    {
                        $main = app(MainAttribute::class)->create([
                            'id' => uniqid(),
                            'system_type_id' => $this->systemType,
                            'name' => $this->removeBlankSpace($row[0]),
                            'sort' => $mainSort
                        ]);

                        $mainSort++;
                    }
                }

                if (!empty($row[1]))
                {
                    $sub = app(SubAttribute::class)
                        ->where('system_type_id', $this->systemType)
                        ->where('main_attribute_id', $main->id)
                        ->where('name', $this->removeBlankSpace($row[1]))
                        ->first();
                    
                    if (!$sub)
                    {
                        $sub = app(SubAttribute::class)->create([
                            'id' => uniqid(),
                            'system_type_id' => $this->systemType,
                            'main_attribute_id' => $main->id,
                            'name' => $this->removeBlankSpace($row[1]),
                            'sort' => $subSort
                        ]);

                        $subSort++;
                    }
                }

                if (!empty($row[4]))
                {
                    $req = app(Regulation::class)
                        ->where('system_type_id', $this->systemType)
                        ->where('main_attribute_id', $main->id)
                        ->where('sub_attribute_id', $sub->id)
                        ->where('no', $this->removeBlankSpace($row[2]))
                        ->where('clause', $this->removeBlankSpace($row[4]))
                        ->first();

                    if (!$req)
                    {
                        switch($this->removeBlankSpace($row[3]))
                        {
                            case '普':
                                $level = 1;
                                break;
                            case '中':
                                $level = 2;
                                break;
                            default:
                                $level = 3;
                                break;
                        }

                        app(Regulation::class)->create([
                            'id' => uniqid(),
                            'system_type_id' => $this->systemType,
                            'regulation_version_id' => $this->versionId,
                            'main_attribute_id' => $main->id,
                            'sub_attribute_id' => $sub->id,
                            'no' => $this->removeBlankSpace($row[2]),
                            'clause' => $this->removeBlankSpace($row[4]),
                            'level' => $level
                        ]);
                    }
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function removeBlankSpace($str) 
    {
        return preg_replace('/\s(?=)/', '', $str);
    }
}
