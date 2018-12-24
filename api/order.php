<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/17
 * Time: 16:11
 */
header("content-type:text/html;charset=utf-8");
$dsn = "mysql:host=47.95.223.175;dbname=weiqing";
$db = new \PDO($dsn,'weiqing','Xj3jSYan6e');
$openid = $_GET['openid'];//用户openid,主要知道是那个人下的单
$actid = $_GET['actid'];//活动id
$total = $_GET['total'];//订单数量
$fee =$_GET['fee'];//费用
$status = '1';//状态 下单1已付款2已核销3已关闭4
$transid = time().rand(1000,9999).rand(1000,9999);//订单编号
$qrcode = uniqid();//验票码
$expiretime = $expiretime;//订单过期时间
$createtime = time();//创建时间
$paytime = '0';//支付时间
$query = "INSERT INTO ims_hticket_order (openid,actid,fee,total,status,transid,qrcode,expiretime,createtime,paytime) VALUES ('$openid',$actid,$fee,$total,$status,$transid,'$qrcode',$expiretime,$createtime,$paytime)";
$res = $db->exec($query);
echo $res;


