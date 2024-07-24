<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeeRateTable extends AbstractModel
{
    protected $table = 'fee_rate_tables';
    protected $guarded = [];

    public function getFieldProperties()
    {
    }

    public function getSearchResult($filters = [], $orderBy = 'eng_country_name', $sort = 'ASC')
    {
        $query = $this->newModelQuery();

        if (Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('eng_country_name', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('country_name', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('country_code', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('area_code', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('area_name', 'like', '%' . $filters['keyword'] . '%');
            });
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows'] ?? 20);
        $results->appends($filters);

        return $results;
    }
}
