<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>在线支付</title>
  <style>TD {
   FONT-SIZE: 9pt; line-height: 14pt
 }
 </style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
  <table height="400" cellspacing="0" cellpadding="0" width="500" background="<?php echo base_url()?>public/img/cmbchinabg.jpg" border="0" align="center" style="border:#000000 1px double"> 
    <tbody><tr>
      <td valign="top">
        <table cellspacing="0" cellpadding="0" width="100%" border="0">
          <tbody><tr>
            <td><img height="86" src="<?php echo base_url()?>public/img/blank.gif" width="10"></td>
            <td>&nbsp;</td></tr>
            <tr>
              <td width="236"><img height="1" src="<?php echo base_url()?>public/img/blank.gif" width="236"></td>
              <td valign="top"> 
                <p>支付结果：<font color="#FF0000"><b><?php echo $result ?></b></font><br>
                  <br>
                  您所支付的款项：<br>
                  <br>
                  人民币：<b><font color="#FF0000"><?php echo $amount ?></font>元</b><br>
                </p>
                <p>&nbsp;</p></td></tr></tbody></table></td></tr></tbody>
</table>
</body>
</html>