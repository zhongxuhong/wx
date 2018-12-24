<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19
 * Time: 10:30
 */
//付款后回调更新订单表数据
header("content-type:text/html;charset=utf-8");
$dsn = "mysql:host=47.95.223.175;dbname=weiqing";
$db = new \PDO($dsn,'weiqing','Xj3jSYan6e');
$transid = $_GET['transid'];
if($transid){
    $time = time();
$sql = "update ims_hticket_order set status=2,paytime='$time' where transid='$transid'";
    $res = $db->exec($sql);
    echo $res;
}else{
    echo '缺少参数transid';
}
