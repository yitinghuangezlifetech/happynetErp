<?php

namespace App\Imports;

use App\Models\FeeRate;
use App\Models\FeeRateTable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RateImport implements ToCollection
{
    public $feeRate;

    public function __construct(FeeRate $feeRate)
    {
        $this->feeRate = $feeRate;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key > 0) {
                $type = 1;

                $countryCode = $this->removeBlankSpace($row[2]);

                if ($countryCode == '0') {
                    $type = 2;
                }

                app(FeeRateTable::class)->create([
                    'id' => uniqid(),
                    'fee_rate_id' => $this->feeRate->id,
                    'eng_country_name' => $this->removeBlankSpace($row[0]),
                    'country_name' => $this->removeBlankSpace($row[1]),
                    'country_code' => $countryCode,
                    'area_code' => $this->removeBlankSpace($row[3]),
                    'area_name' => $this->removeBlankSpace($row[4]),
                    'unit' => $this->removeBlankSpace($row[5]),
                    'price' => $this->removeBlankSpace($row[6]),
                    'unit_2' => $this->removeBlankSpace($row[7]),
                    'price_2' => $this->removeBlankSpace($row[8]),
                    'type' => $type
                ]);
            }
        }
    }

    public function removeBlankSpace($str)
    {
        return preg_replace('/\s(?=)/', '', $str);
    }
}
