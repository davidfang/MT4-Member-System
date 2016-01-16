<?php
header("Content-type:text/html; charset=utf-8");
define('TEST',2);
//提交地址
if(TEST == 1)
{
	$form_url = 'https://pay.ips.net.cn/ipayment.aspx'; //测试
}
else
{
	$form_url = 'https://pay.ips.com.cn/ipayment.aspx'; //正式
}


//商户号
$Mer_code = $p1_MerId;

//商户证书：登陆http://merchant.ips.com.cn/商户后台下载的商户证书内容
$Mer_key = $merchantKey;

//商户订单编号
$Billno = $my_id;

//订单金额(保留2位小数)
$Amount = number_format($amount, 2, '.', '');

//订单日期
$Date = date('Ymd');

//币种
$Currency_Type = 'RMB';

//支付卡种
$Gateway_Type = '01';

//语言
$Lang = 'GB';

//支付结果成功返回的商户URL
$Merchanturl = $bgurl;

//支付结果失败返回的商户URL
$FailUrl = $bgurl;

//支付结果错误返回的商户URL
$ErrorUrl = "";

//商户数据包
$Attach = $username.'#'.$remark;

//显示金额
//$DispAmount = $_POST['DispAmount'];

//订单支付接口加密方式
$OrderEncodeType = 5;

//交易返回接口加密方式 
$RetEncodeType = 17;

//返回方式
$Rettype = 1;

//Server to Server 返回页面URL
$ServerUrl = $bgurl;
//OrderEncodeType设置为5，且在订单支付接口的Signmd5字段中存放MD5摘要认证信息。
//交易提交接口MD5摘要认证的明文按照指定参数名与值的内容连接起来，将证书同时拼接到参数字符串尾部进行md5加密之后再转换成小写，明文信息如下：
//billno+【订单编号】+ currencytype +【币种】+ amount +【订单金额】+ date +【订单日期】+ orderencodetype +【订单支付接口加密方式】+【商户内部证书字符串】
//例:(billno000001000123currencytypeRMBamount13.45date20031205orderencodetype5GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)
//订单支付接口的Md5摘要，原文=订单号+金额+日期+支付币种+商户证书 
$orge = 'billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key ;
//echo '明文:'.$orge ;
//$SignMD5 = md5('billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key);
$SignMD5 = md5($orge) ;
//echo '密文:'.$SignMD5 ;
//sleep(20);
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form action="<?php echo $form_url ?>" method="post" id="frm1">
      <input type="hidden" name="Mer_code" value="<?php echo $Mer_code ?>">
      <input type="hidden" name="Billno" value="<?php echo $Billno ?>">
      <input type="hidden" name="Amount" value="<?php echo $Amount ?>" >
      <input type="hidden" name="Date" value="<?php echo $Date ?>">
      <input type="hidden" name="Currency_Type" value="<?php echo $Currency_Type ?>">
      <input type="hidden" name="Gateway_Type" value="<?php echo $Gateway_Type ?>">
      <input type="hidden" name="Lang" value="<?php echo $Lang ?>">
      <input type="hidden" name="Merchanturl" value="<?php echo $Merchanturl ?>">
      <input type="hidden" name="FailUrl" value="<?php echo $FailUrl ?>">
      <input type="hidden" name="ErrorUrl" value="<?php echo $ErrorUrl ?>">
      <input type="hidden" name="Attach" value="<?php echo $Attach ?>">
      <input type="hidden" name="OrderEncodeType" value="<?php echo $OrderEncodeType ?>">
      <input type="hidden" name="RetEncodeType" value="<?php echo $RetEncodeType ?>">
      <input type="hidden" name="Rettype" value="<?php echo $Rettype ?>">
      <input type="hidden" name="ServerUrl" value="<?php echo $ServerUrl ?>">
      <input type="hidden" name="SignMD5" value="<?php echo $SignMD5 ?>">
      <input type="hidden" name="DoCredit" value="1">
      <input type="hidden" name="Bankco" value="<?php echo $bank ?>">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
