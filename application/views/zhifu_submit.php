<?php
/* *
 *功能：即时到账交易接口接入页
 *版本：3.0
 *日期：2013-08-01
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
 ////////////////////////////////////请求参数//////////////////////////////////////

		//参数编码字符集(必选)
		$input_charset = 'UTF-8';

		//接口版本(必选)固定值:V3.0
		$interface_version = 'V3.0';

		//商家号（必填）
		$merchant_code = $p1_MerId;

		//后台通知地址(必填)
		$notify_url = $bgurl;

		//定单金额（必填）
		$order_amount = $amount;

		//商家定单号(必填)
		$order_no = $my_id;

		//商家定单时间(必填)
		$order_time = date('Y-m-d H:i:s');

		//签名方式(必填)
		$sign_type = 'MD5';

		//商品编号(选填)
		$product_code = '';

		//商品描述（选填）
		$product_desc = '';

		//商品名称（必填）
		$product_name = $username;

		//端口数量(选填)
		$product_num = '';

		//页面跳转同步通知地址(选填)
		$return_url = $bgurl;

		//业务类型(必填)
		$service_type = 'direct_pay';

		//商品展示地址(选填)
		$show_url = '';

		//公用业务扩展参数（选填）
		$extend_param = '';

		//公用业务回传参数（选填）
		$extra_return_param = '';

		// 直联通道代码（选填）
		$bank_code = $bank;

		//客户端IP（选填）
		$client_ip = '';

	/* 注  new String(参数.getBytes("UTF-8"),"此页面编码格式"); 若为GBK编码 则替换UTF-8 为GBK*/
	if($product_name != "") {
	  $product_name = mb_convert_encoding($product_name, "UTF-8", "UTF-8");
	}
	if($product_desc != "") {
	  $product_desc = mb_convert_encoding($product_desc, "UTF-8", "UTF-8");
	}
	if($extend_param != "") {
	  $extend_param = mb_convert_encoding($extend_param, "UTF-8", "UTF-8");
	}
	if($extra_return_param != "") {
	  $extra_return_param = mb_convert_encoding($extra_return_param, "UTF-8", "UTF-8");
	}
	if($product_code != "") {
	  $product_code = mb_convert_encoding($product_code, "UTF-8", "UTF-8");
	}
	if($return_url != "") {
	  $return_url = mb_convert_encoding($return_url, "UTF-8", "UTF-8");
	}
	if($show_url != "") {
	  $show_url = mb_convert_encoding($show_url, "UTF-8", "UTF-8");
	}


	/*
	**
	 ** 签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，
	 ** 组成规则如下：
	 ** 参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
	 **/
	$signSrc= "";

	//组织订单信息
	if($bank_code != "") {
		$signSrc = $signSrc."bank_code=".$bank_code."&";
	}
	if($client_ip != "") {
                $signSrc = $signSrc."client_ip=".$client_ip."&";
	}
	if($extend_param != "") {
		$signSrc = $signSrc."extend_param=".$extend_param."&";
	}
	if($extra_return_param != "") {
		$signSrc = $signSrc."extra_return_param=".$extra_return_param."&";
	}
	if($input_charset != "") {
		$signSrc = $signSrc."input_charset=".$input_charset."&";
	}
	if($interface_version != "") {
		$signSrc = $signSrc."interface_version=".$interface_version."&";
	}
	if($merchant_code != "") {
		$signSrc = $signSrc."merchant_code=".$merchant_code."&";
	}
	if($notify_url != "") {
		$signSrc = $signSrc."notify_url=".$notify_url."&";
	}
	if($order_amount != "") {
		$signSrc = $signSrc."order_amount=".$order_amount."&";
	}
	if($order_no != "") {
		$signSrc = $signSrc."order_no=".$order_no."&";
	}
	if($order_time != "") {
		$signSrc = $signSrc."order_time=".$order_time."&";
	}
	if($product_code != "") {
		$signSrc = $signSrc."product_code=".$product_code."&";
	}
	if($product_desc != "") {
		$signSrc = $signSrc."product_desc=".$product_desc."&";
	}
	if($product_name != "") {
		$signSrc = $signSrc."product_name=".$product_name."&";
	}
	if($product_num != "") {
		$signSrc = $signSrc."product_num=".$product_num."&";
	}
	if($return_url != "") {
		$signSrc = $signSrc."return_url=".$return_url."&";
	}
	if($service_type != "") {
		$signSrc = $signSrc."service_type=".$service_type."&";
	}
	if($show_url != "") {
		$signSrc = $signSrc."show_url=".$show_url."&";
	}
        //设置密钥
	$key = $merchantKey; // <支付密钥> 注:此处密钥必须与商家后台里的密钥一致
	$signSrc = $signSrc."key=".$key;

	$singInfo = $signSrc;
	//echo "singInfo=".$singInfo."<br>";

	//签名
	$sign = md5($singInfo);
	//echo "sign=".$sign."<br>";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad="document.dinpayForm.submit();">
正在跳转 ...
<form name="dinpayForm" method="post" action="https://pay.dinpay.com//gateway?input_charset=UTF-8"><!-- 注意 非UTF-8编码的商家网站 此地址必须后接编码格式 -->
	<input type="hidden" name="sign" value="<? echo $sign?>" />
	<input type="hidden" name="merchant_code" value="<? echo $merchant_code?>" />
	<input type="hidden" name="bank_code" value="<? echo $bank_code?>"/>
	<input type="hidden" name="order_no" value="<? echo $order_no?>"/>
	<input type="hidden" name="order_amount" value="<? echo $order_amount?>"/>
	<input type="hidden" name="service_type" value="<? echo $service_type?>"/>
	<input type="hidden" name="input_charset" value="<? echo $input_charset?>"/>
	<input type="hidden" name="notify_url" value="<? echo $notify_url?>">
	<input type="hidden" name="interface_version" value="<? echo $interface_version?>"/>
	<input type="hidden" name="sign_type" value="<? echo $sign_type?>"/>
	<input type="hidden" name="order_time" value="<? echo $order_time?>"/>
	<input type="hidden" name="product_name" value="<? echo $product_name?>"/>
	<input Type="hidden" Name="client_ip" value="<? echo $client_ip?>"/>
	<input Type="hidden" Name="extend_param" value="<? echo $extend_param?>"/>
	<input Type="hidden" Name="extra_return_param" value="<? echo $extra_return_param?>"/>
	<input Type="hidden" Name="product_code" value="<? echo $product_code?>"/>
	<input Type="hidden" Name="product_desc" value="<? echo $product_desc?>"/>
	<input Type="hidden" Name="product_num" value="<? echo $product_num?>"/>
	<input Type="hidden" Name="return_url" value="<? echo $return_url?>"/>
	<input Type="hidden" Name="show_url" value="<? echo $show_url?>"/>
	</form>
</body>
</html>