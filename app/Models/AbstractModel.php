<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

use App\Models\Contracts\InterfaceModel;

abstract class AbstractModel extends Model implements InterfaceModel
{

    /* 在使用uuid的情況下, 取消資料表primary 自動遞增
     * 防止取資料時, id 回傳 0 而不是已寫入資料表的uuid
     */
    public $incrementing = false;

    abstract function getFieldProperties();

    public function getListByFilters($menuDetails, $filters = [], $orderBy = 'created_at', $sort = 'DESC')
    {
        $query = $this->newModelQuery();

        if (Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (count($filters) > 0) {
            $pass = 2;

            foreach ($menuDetails as $detail) {
                if (isset($filters[$detail->field]) && !empty($filters[$detail->field]) || !empty($filters['start_day']) && !empty($filters['end_day'])) {
                    if ($detail->field == 'parent_id') {
                        if ($pass == 2) {
                            if (isset($filters['id']) && isset($filters['parent_id'])) {
                                $query->where(function ($q) use ($filters) {
                                    $q->where('id', $filters['id'])
                                        ->orWhere('parent_id', $filters['parent_id']);
                                });
                            } else if (isset($filters['id'])) {
                                $query->where('id', $filters['id']);
                            } else if (isset($filters['parent_id'])) {
                                $query->where('parent_id', $filters['parent_id']);
                            }

                            $pass = 1;
                        }
                    } else {
                        if (isset($filters['id']) && isset($filters['parent_id'])) {
                            $query->where(function ($q) use ($filters) {
                                $q->where('id', $filters['id'])
                                    ->orWhere('parent_id', $filters['parent_id']);
                            });
                        }
                        switch ($detail->type) {
                            case 'text':
                            case 'text_area':
                            case 'number':
                            case 'email':
                                $query->where($detail->field, 'like', '%' . $filters[$detail->field] . '%');
                                break;
                            case 'date':
                            case 'date_time':
                                if (isset($filters['start_day']) && isset($filters['end_day'])) {
                                    $query->where($detail->field, '<=', $filters['start_day'])
                                        ->where($detail->field, '>=', $filters['end_day']);
                                }
                                break;
                            default:
                                $query->where($detail->field, $filters[$detail->field]);
                                break;
                        }
                    }
                }
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows'] ?? 20);
        $results->appends($filters);

        return $results;
    }

    public function getAllDataByFilters($menuDetails, $filters = [], $orderBy = 'created_at', $sort = 'DESC')
    {
        $query = $this->newModelQuery();

        if (Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (count($filters) > 0) {
            foreach ($menuDetails as $detail) {
                if (isset($filters[$detail->field]) && !empty($filters[$detail->field]) || !empty($filters['start_day']) && !empty($filters['end_day'])) {
                    switch ($detail->type) {
                        case 'text':
                        case 'text_area':
                        case 'number':
                        case 'email':
                            $query->where($detail->field, 'like', '%' . $filters[$detail->field] . '%');
                            break;
                        case 'date':
                        case 'date_time':
                            if (isset($filters['start_day']) && isset($filters['end_day'])) {
                                $query->where($detail->field, '<=', $filters['start_day'])
                                    ->where($detail->field, '>=', $filters['end_day']);
                            }
                            break;
                        default:
                            $query->where($detail->field, $filters[$detail->field]);
                            break;
                    }
                }
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();

        return $results;
    }

    public function getMenuBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function createData($formData)
    {
        $id = $this->create($formData)->id;
        return $id;
    }

    public function getData($id)
    {
        return $this->where('id', $id)->first();
    }

    public function updateData($id, $formData)
    {
        $this->where('id', $id)->update($formData);
    }

    public function deleteData($id)
    {
        $this->where('id', $id)->delete();
    }

    public function deleteMultipleData($ids)
    {
        $this->whereIn('id', $ids)->delete();
    }

    /**
     * 還原因attribute所改變的檔案路徑
     * @author Wayne <wayne@howdesign.com.tw>
     * @param $key
     * @return mixed
     */
    public function getOriginalPath($key)
    {
        if (($key != '' || !is_null($key)) && isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }

    /**
     * 取得資料表所有欄位
     *
     * @author Wayne <wayne@howdesign.com.tw>
     * @return mixed
     */
    public function getTableColums()
    {
        return DB::getSchemaBuilder()->getColumnListing($this->table);
    }

    /**
     * 取得指定資料表的所有欄位
     *
     * @author Wayne <wayne@howdesign.com.tw>
     * @param string $tableName 資料表名稱
     * @return mixed
     */
    public function getTableColumsByTableName(string $tableName)
    {
        return DB::getSchemaBuilder()->getColumnListing($tableName);
    }

    /**
     * 取得資料庫所有的資料表
     *
     * @author Wayne <wayne@howdesign.com.tw>
     * @return mixed
     */
    public function getDbTables()
    {
        return DB::connection()->getDoctrineSchemaManager()->listTableNames();
    }

    /**
     * 取得資料表指定之欄位是否存在
     *
     * @author Wayne <wayne@howdesign.com.tw>
     * @param string $columnName 欄位名稱
     * @return mixed
     */
    public function checkColumnExist($columnName)
    {
        if (Schema::hasColumn($this->table, $columnName)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 取得sql command 內容
     *
     * @author Wayne <wayne@howdesign.com.tw>
     * @param $query
     */
    public function getQuerySqlBindings($query)
    {
        $sql = str_replace("?", "%s", $query->toSql());
        $sql = sprintf($sql, ...$query->getBindings());

        dd($sql);
    }
}
