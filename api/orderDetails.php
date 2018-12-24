<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 14:23
 */

header("content-type:text/html;charset=utf-8");
$dsn = "mysql:host=47.95.223.175;dbname=weiqing";
$db = new \PDO($dsn,'weiqing','Xj3jSYan6e');
   $openid = $_GET['openid'];
   $actid = $_GET['actid'];
   $id = $_GET['id'];
   $sql = "select o.*,a.proname,a.placedetail,a.author from ims_hticket_order as o inner join
 ims_hticket_activity as a on o.actid=a.id where o.openid='$openid' and o.actid=$actid  order by o.createtime desc";
$res = $db->query($sql);
$res->setFetchMode(PDO::FETCH_ASSOC);
$result = $res->fetchAll();
echo json_encode($result['0']);