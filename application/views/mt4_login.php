<?php $this->load->view('login_header')?>
<title><?php echo $title ?></title>
<style type="text/css">.popover{width:400px;}</style>
</head>
<body>
	<div id="ext_toolbar" style="display:none">
		<div id="ext_toolbar_close">
			<span style="display:none;">关闭</span>
		</div>
		<div id="ext_toolbar_install">
			<a class="btn" href="http://www.google.com/intl/zh-CN/chrome/" target="_blank">下载安装</a>
		</div>
		<div id="ext_toolbar_text" style="padding: 9px 10px 6px 38px; overflow: hidden; white-space: nowrap; -webkit-user-select: none; cursor: default; width: 1254px; background-position: 5px 5px;">
			温馨提醒：检测到您在使用IE浏览器访问本站，推荐使用Chrome、<a href="http://www.firefox.com.cn/download/" target="_blank">Firefox</a>或<a href="http://www.opera.com/zh-cn" target="_blank">Opera</a>等现代浏览器访问本站点，其他浏览器访问不保证能正常使用本站所有功能
		</div>
	</div>
	<div class="container">
		<div class="form-signin">
			<div id="head_info">
				<span class="label label-info">温馨提示</span>
				<div class="alert alert-info" style="margin-top:20px">
					<p>账户资金注入及提取账户资金等均需要登陆网站会员系统完成，请先登录。</p>
					<p>请您认真保管自己的帐号和密码，对于因您密码泄露而造成的任何损失，公司将不负担责任。</p>
				</div>
			</div>
			<?php 
			$vcode_url = site_url('user/vcode');
			$attr = array('method'=>'post','id'=>'form_mt4_login','class'=>'form-horizontal');
			echo form_open('user/mt4login',$attr);
			?>
			<div class="alter alert-error">
				<?php if(validation_errors()){?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo validation_errors();?>
				<?php }?>

				<?php
				if (isset($error)) {?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo $error; }?>
			</div>
			<?php 
			$attr = array('method'=>'post','id'=>'form_mt4_login','class'=>'form-horizontal');
			echo form_open('user/mt4login',$attr);?>
			<div class="control-group">
				<label class="control-label" for="username">MT4帐号</label>
				<div class="controls">
					<?php 
					$data = array('name'=>'mt4_username','id'=>'mt4_username','maxlength'=>'20','placeholder'=>'MT4实盘帐号');
					echo form_input($data);
					?>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="password">密码</label>
				<div class="controls">
					<?php 
					$data = array('name'=>'mt4_password','id'=>'mt4_password','maxlength'=>'20');
					echo form_password($data);
					?>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="server">服务器</label>
				<div class="controls">
					<select id="server" name="server">
						<option value="">请选择服务器</option>
						<?php foreach ($server as $v){?>
						<option value="<?php echo $v['local_port']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
						<?php }?>
					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="captcha">验证码</label>
				<div class="controls">
					<?php 
					$data = array('name'=>'captcha','id'=>'captcha','maxlength'=>'10','placeholder'=>'不区分大小写','style'=>'width:100px');
					echo form_input($data);
					?>
					<span class="help-inline"><font color="red">*</font><span id="vcode"><?php echo $vcode ?></span></span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary" id="do_mt4_login">登陆</button>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<p>
						<a href="#" id="login_error" data-content="<strong>如何启用cookie？</strong><br />
							启用cookies，请根据您的浏览器版本按以下说明操作。<br />
							<strong>Mozilla Firefox (1.4或更高版本)</strong><br />
							打开“工具”菜单，选择“选项”选择面板左边的“隐私”按纽，在Cookies选项下选择“启用cookie”，单击“确定”。<br />
							<strong>Netscape 7.1/Mozilla 5.0</strong><br />
							从“编辑”菜单中选择“个性设置”，单击“隐私和安全性”旁的按键展开该项，在“隐私和安全性”下选择“Cookie”，选择“启用所有cookie”，单击“确定”。<br />
							<strong>Microsoft Internet Explorer 6.0+</strong><br />
							在工具菜单中单击“Internet选项”，单击“隐私”选项卡，在“设置”中点击“默认”按纽(或手动将滑条调到“中”)，单击“确定”。<br />
							<strong>Chrome</strong><br />
							菜单中单击“设置”项打开设置页面，单击“显示高级设置...”，单击“内容设置...”，在Cookie一栏选中“允许设置本地数据（推荐）”，单击“完成”。
							"
							data-original-title="您的浏览器没有开启cookie，无法登录" data-placement="right" data-trigger="hover" data-toggle="popover">无法登陆？</a>
						</p>
					</div>
					<p class="text-warning" id="testJSEnabled">提示：您的浏览器未开启Javascript，本网站将无法正常运行，请先开启Javascript</p>
					<p class="text-warning" id="testCookieEnabled">提示：您的浏览器未开启Cookie，本网站将无法正常运行，请先开启Cookie</p>
					<p class="text-warning" id="testIE6" style="display:none">提示：本系统不支持IE6及更低版本浏览器，请升级您的浏览器</p>
				</div>
				<?php echo form_close()?>
			</div>
			
			<script type="text/javascript">
			function checkCookieEnabled(){
		//检查浏览器是否开启Cookie 
		var cookie_enable = false; 
		if(navigator.cookiesEnabled) 
			return true; 
		document.cookie = "checkCookie=yes"; 
		var cookieSet = document.cookie; 
		if(cookieSet.indexOf("checkCookie=yes") > -1) 
			cookie_enable = true; 
		document.cookie = ""; 
		return cookie_enable; 
	}

	$(function(){
		$("#testJSEnabled").remove();
		if(checkCookieEnabled() == true){
			$("#testCookieEnabled").remove();
		}

		$("#login_error").popover("hide");

		$("#vcode img").attr('title','看不清楚？点击图片切换验证码');
		$("#vcode").click(getVCode);

		var userAgent = window.navigator.userAgent;
		if (userAgent.indexOf("MSIE") > 0) {
			$("#ext_toolbar").show().css("width",document.body.clientWidth + "px");
		}

		$("#ext_toolbar_close").click(function(){
			$("#ext_toolbar").hide();
		});
		//验证码
		function getVCode(){
			$.get(<?php echo '"' . $vcode_url . '"'?> + '?'+ Math.random(),
				function(data){
					$("#vcode").html(data);
					$("#vcode img").attr('title','看不清楚？点击图片切换验证码');
				});
		}

		$("#do_mt4_login").click(function(){
			var username = $("#mt4_username").val();
			var password = $("#mt4_password").val();
			var server = $("#server").val();
			var vcode = $("#captcha").val();

			if (username.length == 0 || password.length == 0 || server.length == 0 || vcode.length == 0) {
				alert("必要信息未填写");
				return false;
			};

			if (password.length < 6) {
				alert("密码长度至少为6位");
				return false;
			};

			
			$('#loading').modal({
				backdrop: false
			});
			$("#form_mt4_login").submit();
			$(this).attr("disabled",true);
			return true;
		});
	});
</script>

<!--[if IE 6]>
	<script type="text/javascript">
		$("#testIE6").css("display","block");
	</script>
	<![endif]-->
	<?php $this->load->view('footer')?>