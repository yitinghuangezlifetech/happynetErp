<?php
header("Content-Type: text/html;charset=utf-8");
define("DBHOST", "61.60.145.170");
define("DATABASE", "billing");
// define("USERNAME", "root");
// define("PASSWORD", "");
define("USERNAME", "hperp");
define("PASSWORD", "qAi_jvlL_1d9adj");

class Db
{
    public $conn;
    public $resource;

    public function __construct()
    {
        $this->conn = new mysqli(DBHOST, USERNAME, PASSWORD, DATABASE);
        mysqli_query($this->conn, 'SET NAMES utf8');

        if (mysqli_connect_errno()) {
            die("連接失敗 : " . mysqli_connect_error());
        }
    }

    public function getData($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function getList($sql)
    {
        $arr = [];
        $result = mysqli_query($this->conn, $sql);

        while ($row = $result->fetch_assoc()) {
            array_push($arr, $row);
        }

        return $arr;
    }

    public function create($formData, $tableName)
    {
        $column = '';
        $vaule = '';
        $com = '';

        if (is_array($formData) && count($formData) > 0) {

            foreach ($formData as $key => $val) {
                $column .= $com . $key;
                $vaule .= $com . '"' . $val . '"';
                $com = ',';
            }

            $sql = 'insert into ' . $tableName . ' (' . $column . ') values (' . $vaule . ')';
            $query = $this->conn->prepare($sql);
            $query->execute();
        }
    }

    public function update($id, $formData, $tableName)
    {
        $column = '';
        $vaule = '';
        $com = '';

        foreach ($formData as $key => $val) {
            $column .= $com . $key . ' = "' . $val . '"';
            $com = ',';
        }

        $sql = 'update ' . $tableName . ' set ' . $column . ' where id = ' . $id;
        $query = $this->conn->prepare($sql);
        $query->execute();
    }

    public function getLastId()
    {
        return mysqli_insert_id();
    }

    public function getDataByOrderNo($orderNo, $tableName)
    {
        $sql = 'select * from ' . $tableName . ' where order_no = "' . $orderNo . '"';

        return $this->getData($sql);
    }

    public function getMemberByMobile($mobile, $tableName)
    {
        $sql = 'select * from ' . $tableName . ' where mobile = "' . $mobile . '"';

        return $this->getData($sql);
    }
}
