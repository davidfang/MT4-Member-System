<!doctype html>
<html>
<head>
<title>支付结果</title>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<style type="text/css">
td {
	text-align: center
}
</style>
</head>

<BODY>
	<div align="center">
		<h2 align="center">支付结果</h2>
		<table width="500" border="1" style="border-collapse: collapse"
			bordercolor="green" align="center">
			<tr>
				<td id="payResult">处理结果</td>
				<td>
                        <?PHP
																								if ($msg == 'success') {
																									echo '交易成功！';
																								} else if ($msg == 'false') {
																									echo '交易失败！';
																								} else {
																									echo '签名错误，非法返回！';
																								}
																								?>
                    </td>
			</tr>
		</table>
	</div>
</BODY>
</HTML>