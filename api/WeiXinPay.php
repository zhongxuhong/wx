<?php
/**微信小程序支付类统一下单*/
header("content-type:text/html;charset=utf-8");
   $openid = $_GET['openid'];
//   $openid = "ovPyP4nrMPutha8OYiDyLzmKHmS8";
   $outTradeNo = time().rand(1000,9999);//订单编号
//    $totalFee = $_GET['numprice']*100;//金额
   $totalFee = 1;
   $weixinpay = new WeiXinPay($openid,$outTradeNo,$totalFee);
//        echo 'openid是'.$openid.'   价格是'.$totalFee;
//       exit;
       $res = $weixinpay ->pay();
         echo json_encode($res);
//微信支付类
class WeiXinPay{
  //微信小程序身份的唯一标识
  protected $APPID = "wx4a8cc38a88c07c80";//填写您的appid。微信公众平台里的
  protected $APPSECRET = "f02ace2c2f3733e3d965d91524e8322f";
  //受理商ID，身份标识
  protected $MCHID = "1501360301";//商户id
  //商户支付密钥Key
  protected $KEY = 'dsfsdfsdfsdfsdfsdfdsfghfghgfhgf2';
  //回调通知接口
  protected $APPURL =   'http://tj.ytsshop.com/payment/wechat/native.php';
  //交易类型
  protected $TRADETYPE = 'JSAPI';
  //商品类型信息
  protected $BODY = '测试商品';
  //微信支付类的构造函数
  function __construct($openid,$outTradeNo,$totalFee){
    $this->openid = $openid; //小程序用户唯一标识
    $this->outTradeNo = $outTradeNo; //商品编号
    $this->totalFee = $totalFee; //总价
  }
  //微信支付类向外暴露的支付接口
  public function pay(){
    $result = $this->weixinapp();
    return $result;
  }
   //对微信统一下单接口返回的支付相关数据进行处理
   public function weixinapp(){
     $unifiedorder=$this->unifiedorder();
     $parameters=array(
     'appId'=>$this->APPID,//小程序ID
     'timeStamp'=>''.time().'',//时间戳
     'nonceStr'=>$this->createNoncestr(),//随机串
     'package'=>'prepay_id='.$unifiedorder['prepay_id'],//数据包
     'signType'=>'MD5'//签名方式
       );
     $parameters['paySign']=$this->getSign($parameters);
     return $parameters;
   }
  /*
   *请求微信统一下单接口
   */
  public function unifiedorder(){
    $parameters = array(
      'appid' => $this->APPID,//小程序id
      'mch_id'=> $this->MCHID,//商户id
      'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],//终端ip
      'notify_url'=>$this->APPURL, //通知地址
      'nonce_str'=> $this->createNoncestr(),//随机字符串
      'out_trade_no'=>$this->outTradeNo,//商户订单编号
      'total_fee'=>floatval($this->totalFee), //总金额
      'openid'=>$this->openid,//用户openid
      'trade_type'=>$this->TRADETYPE,//交易类型
      'body' =>$this->BODY, //商品信息
    );
    $parameters['sign'] = $this->getSign($parameters);
    $xmlData = $this->arrayToXml($parameters);
    $xml_result = $this->postXmlCurl($xmlData,'https://api.mch.weixin.qq.com/pay/unifiedorder',60);
    $result = $this->xmlToArray($xml_result);
    return $result;
  }
  //数组转字符串xml方法
  protected function arrayToXml($arr){
    $xml = "<xml>";
    foreach ($arr as $key=>$val)
    {
      if (is_numeric($val)){
        $xml.="<".$key.">".$val."</".$key.">";
      }else{
         $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
      }
    }
    $xml.="</xml>";
    return $xml;
  }
  //xml转数组
  protected function xmlToArray($xml){
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
  }
  //发送post请求方法,xml数据
  private static function postXmlCurl($xml, $url, $second = 30)
  {
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    set_time_limit(0);
    //运行curl
    $data = curl_exec($ch);
    //返回结果
    if ($data) {
      curl_close($ch);
      return $data;
    } else {
      $error = curl_errno($ch);
      curl_close($ch);
      throw new WxPayException("curl出错，错误码:$error");
    }
  }
  /*
   * 对要发送到微信统一下单接口的数据进行签名
   */
  protected function getSign($Obj){
     foreach ($Obj as $k => $v){
     $Parameters[$k] = $v;
     }
     //签名步骤一：按字典序排序参数
     ksort($Parameters);
     $String = $this->formatBizQueryParaMap($Parameters, false);
     //签名步骤二：在string后加入KEY
     $String = $String."&key=".$this->KEY;
     //签名步骤三：MD5加密
     $String = md5($String);
     //签名步骤四：所有字符转为大写
     $result_ = strtoupper($String);
     return $result_;
   }
  /*
   *排序并格式化参数方法，签名时需要使用
   */
  protected function formatBizQueryParaMap($paraMap, $urlencode)
  {
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v)
    {
      if($urlencode)
      {
        $v = urlencode($v);
      }
      //$buff .= strtolower($k) . "=" . $v . "&";
      $buff .= $k . "=" . $v . "&";
    }
    $reqPar;
    if (strlen($buff) > 0)
    {
      $reqPar = substr($buff, 0, strlen($buff)-1);
    }
    return $reqPar;
  }
  /*
   * 生成随机字符串方法
   */
  protected function createNoncestr($length = 32 ){
     $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
     $str ="";
     for ( $i = 0; $i < $length; $i++ ) {
     $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
     }
     return $str;
     }
}



