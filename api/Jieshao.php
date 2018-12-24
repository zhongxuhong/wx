<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6
 * Time: 16:02
 */
header("content-type:text/html;charset=utf-8");
$dsn = "mysql:host=47.95.223.175;dbname=weiqing";
$db = new \PDO($dsn,'weiqing','Xj3jSYan6e');
$id = $_GET['id'];
//print_r($id);exit;
$sql = "select* from ims_hticket_activity where id = $id ";
$res = $db->query($sql);
$res->setFetchMode(PDO::FETCH_ASSOC);
$result = $res->fetchAll();
echo json_encode($result);

