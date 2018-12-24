<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/16
 * Time: 9:27
 */
//生成二维码
include 'phpqrcode.php';
//$qrcode = 781533433073;
//$url = "https://www.baidu.com";
$url = "http://tj.ytsshop.com/app/index.php?i=2&c=entry&id=1112&op=o4xM50wE-iZlsZP3yXOtK6zWVUko&do=master&m=hawk_ticket";
http://tj.ytsshop.com/app/index.php?i=2&c=entry&raw=&do=qr&m=hawk_ticket
//$content = $url.'?qrcode='.$qrcode;
//echo $content;
$imgcode = QRcode::png($url);
echo $imgcode;