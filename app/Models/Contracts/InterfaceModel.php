<?php

namespace App\Models\Contracts;

interface InterfaceModel
{

    public function getFieldProperties();
    public function getListByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC');
    public function getAllDataByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC');
    public function createData($formData);
    public function editData($id);
    public function updateData($formData, $id);
    public function deleteData($id);
    public function deleteMultipleData($ids);
}