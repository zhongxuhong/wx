<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19
 * Time: 13:34
 */
header("content-type:text/html;charset=utf-8");
$dsn = "mysql:host=47.95.223.175;dbname=weiqing";
$db = new \PDO($dsn,'weiqing','Xj3jSYan6e');
$current = $_GET['current'];
$openid = $_GET['openid'];
//print_r($current);exit;
if($current == 0){
    $status  = $current+1;
    $sql = "select o.*,a.proname,a.placedetail,a.author, a.expiretime from ims_hticket_order as o inner join
 ims_hticket_activity as a on o.actid=a.id where o.openid='$openid' and o.status = $status order by o.paytime desc limit 0,4";
}else if($current ==2){
    $status=$current;
    $sql = "select o.*,a.proname,a.placedetail,a.author, a.expiretime from ims_hticket_order as o inner join
 ims_hticket_activity as a on o.actid=a.id where o.openid='$openid' and o.status = $status order by o.paytime desc limit 0,4";
}else if($current == 1){
    $status = 3;
    $sql = "select o.*,a.proname,a.placedetail,a.author, a.expiretime from ims_hticket_order as o inner join
 ims_hticket_activity as a on o.actid=a.id where o.openid='$openid' and o.status = $status order by o.paytime desc limit 0,4";
}else{
    echo '缺少参数';exit;
}
$res = $db->query($sql);
$res->setFetchMode(PDO::FETCH_ASSOC);
$result = $res->fetchAll();
echo json_encode($result);

