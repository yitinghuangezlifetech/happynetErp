<?php
require("db.php");

$db = new Db;


$sql = 'select * from ac_main';
$result = $db->getList($sql);


print_r($result);
