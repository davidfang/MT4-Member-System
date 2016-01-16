<?
$MD5key            = $merchantKey;		//MD5私钥
$MerNo             = $p1_MerId;					//商户号
$BillNo            = $my_id;		//[必填]订单号(商户自己产生：要求不重复)
$Amount            = $amount;				//[必填]订单金额

$ReturnURL         = $bgurl; 			//[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过
$Remark            = $username;  //[选填]升级。

$md5src            = $MerNo.$BillNo.$Amount.$ReturnURL.$MD5key;		//校验源字符串
$MD5info           = strtoupper(md5($md5src));		//MD5检验结果

$AdviceURL         = $ReturnURL;   //[必填]支付完成后，后台接收支付结果，可用来更新数据库值
$orderTime         = date("YmdHis");   //[必填]交易时间YYYYMMDDHHMMSS
$defaultBankNumber = $bank;   //[必填]银行代码s

//送货信息(方便维护，请尽量收集！如果没有以下信息提供，请传空值:'')
//因为关系到风险问题和以后商户升级的需要，如果有相应或相似的内容的一定要收集，实在没有的才赋空值,谢谢。

$products          = $Remark;// '------------------物品信息
?>
<html>
<head>
<title>在线支付</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad="document.E_FORM.submit();">
正在处理，请稍等。。。
<form action="https://pay.ecpss.com/sslpayment" method="post" name="E_FORM" id="E_FORM">
  <input type="hidden" name="products" value="<?=$products?>">
  <input type="hidden" name="Amount" value="<?=$Amount?>">
  <input type="hidden" name="defaultBankNumber" value="<?=$defaultBankNumber?>">
  <input type="hidden" name="MerNo" value="<?=$MerNo?>">
  <input type="hidden" name="BillNo" value="<?=$BillNo?>">
  <input type="hidden" name="ReturnURL" value="<?=$ReturnURL?>" >
  <input type="hidden" name="AdviceURL" value="<?=$AdviceURL?>" >
  <input type="hidden" name="orderTime" value="<?=$orderTime?>">
  <input type="hidden" name="MD5info" value="<?=$MD5info?>">
  <input type="hidden" name="Remark" value="<?=$Remark?>">
</form>
</body>
</html>