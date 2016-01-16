<div class="well sidebar-nav">
	<ul class="nav nav-list">
		<li class="nav-header">系统设置</li>
		<li>
			<a href="<?php echo site_url('user/index')?>">密码修改</a>
		</li>
		<li>
			<a href="<?php echo site_url('user/mt4')?>">绑定MT4帐号</a>
		</li>
		<li>
			<a href="<?php echo site_url('user/setinfo/withdraw')?>">取款信息设置</a>
		</li>
		<li class="nav-header">记录查询</li>
		<li><a href="<?php echo site_url('user/checkinfo/deposit')?>">入金查询</a></li>
		<li><a href="<?php echo site_url('user/checkinfo/withdraw')?>">出金查询</a></li>
		<li
		<?php 
        	$data = require (APPPATH . 'config/mail.php');
        	if ($data['is_zz'] != 1) {
          		echo ' style="display:none "';
        	}
        ?>
        ><a href="<?php echo site_url('user/checkinfo/transfer')?>">转账查询</a></li>
<?php 
if (!$this->config->item('only_allow_mt4_login')) {
	$this->load->view('applyaccount');
}
?>
		<li><div class="btn-group">
	123<button class="btn btn-warning dropdown-toggle" data-toggle="dropdown">风格切换<span class="caret"></span></button>
	<ul class="dropdown-menu" id="tm_up">
		<li><a href="#" id="tm0">默认</a></li>
		<li><a href="#" id="tm1">橙色</a></li>
		<li><a href="#" id="tm2">红色</a></li>
		<li><a href="#" id="tm3">蓝色</a></li>
		<li><a href="#" id="tm4">灰色</a></li>
		<li><a href="#" id="tm5">清晰</a></li>
	</ul>
</div></li>
	</ul>
</div>
<script type="text/javascript">
	function setCookie(name,value){
	    var exp  = new Date();  
	    exp.setTime(exp.getTime() + 30*24*60*60*1000);
	    document.cookie = name + "="+ escape (value) +
	    ";expires=" + exp.toGMTString() + ";path=/";
	}
	function getCookie(name){
    	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    	if(arr != null) return unescape(arr[2]);
    	return null;
	}
	function delCookie(name){
	    var exp = new Date();
	    exp.setTime(exp.getTime() - 1);
	    var cval=getCookie(name);
	    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
	}
	$("#tm_up a").click(function(){
		var styles = ["bootstrap.min.css",
					"bootstrap.orange.min.css",
					"bootstrap.red.min.css",
					"bootstrap.blue.min.css",
					"bootstrap.gray.min.css",
					"bootstrap.big.min.css"];
		var id = $(this).attr("id");

		delCookie("default_style");
		switch(id)
		{
			case "tm0":
			setCookie("default_style",styles[0]);
			break;
			case "tm1":
			setCookie("default_style",styles[1]);
			break;
			case "tm2":
			setCookie("default_style",styles[2]);
			break;
			case "tm3":
			setCookie("default_style",styles[3]);
			break;
			case "tm4":
			setCookie("default_style",styles[4]);
			break;
			case "tm5":
			setCookie("default_style",styles[5]);
			break;
			default:
			setCookie("default_style",styles[0]);
		}
		window.location.href = window.location.href
	});
</script>