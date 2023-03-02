<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends AbstractModel {
    use HasFactory;

    protected $table = 'images';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function store() {
        return $this->belongsTo(Store::class, 'reference_id');
    }

    public function getImgAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function setStorePhotoImport($storeId, $photoId) {
        $this->where('type', 'stores')
             ->where('reference_id', $storeId)
             ->where('id', $photoId)
             ->update([
                 'is_main' => 1
             ]);
    }

    public function getStoreMainPhotoByFilters($filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }
            if (!empty($filters['reference_id'])) {
                $query->where('reference_id', $filters['reference_id']);
            }
            if (!empty($filters['is_main'])) {
                $query->where('is_main', $filters['is_main']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->first();
        return $results;
    }

    public function getStorePhotosByFilters($filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }
            if (!empty($filters['reference_id'])) {
                $query->where('reference_id', $filters['reference_id']);
            }
            if (!empty($filters['is_main'])) {
                $query->where('is_main', $filters['is_main']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();
        return $results;
    }
}
