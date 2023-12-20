<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require("db.php");

$db = new Db;


$sql = 'select * from ac_main';
$result = $db->getList($sql);


print_r($result);
