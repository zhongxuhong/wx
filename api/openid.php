<?php
/**获取到openid*/
//声明CODE，获取小程序传过来的CODE
$code = $_GET["code"];
//$code = "0016eC3f29mnDH0FwR4f2FrH3f26eC3a";
//配置小程序appid
$appid = "wx4a8cc38a88c07c80";
//配置小程序appscret
$secret = "f02ace2c2f3733e3d965d91524e8322f";
//api接口
$api = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
$str = file_get_contents($api);
echo $str;
