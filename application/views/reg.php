<?php $this->load->view('login_header')?>
<title><?php echo $title ?></title>
</head>
<body>
<div class="container">
<div class="form-signin">
<?php 
$vcode_url = site_url('user/vcode');
$loading_url = base_url() . '/public/img/loading.gif';
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('user/add',$attr);
?>
<div class="alter alert-error">
	<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();
	echo '<br />' . @$my_error;
	?>
	<?php }?>
</div>
<div class="control-group">
	<label class="control-label" for="username">帐号</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'username','id'=>'username','maxlength'=>'80','placeholder'=>'用户名');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font>长度至少3位</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="email">邮箱</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'email','id'=>'email','maxlength'=>'80','placeholder'=>'Email');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="password">密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password','id'=>'password','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font>长度至少6位</span>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="password_r">确认密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password','id'=>'password_r','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font>请再次填写密码</span>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="captcha">验证码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'captcha','id'=>'captcha','maxlength'=>'10','placeholder'=>'不区分大小写');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font><span id="vcode"><?php echo $vcode ?></span></span>
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'reg_submit','id'=>'reg_submit','value'=>'注册','class'=>'btn');
		echo form_submit($data);
		?>
		<a class="btn btn-primary" href="login" style="margin-left:100px;">返回</a>
	</div>
</div>
<?php echo form_close()?>
</div>
<script type="text/javascript">
	$(function(){
		//getVCode();
		$("#vcode img").attr('title','看不清楚？点击图片切换验证码');
		$("#vcode").click(getVCode);

		function getVCode(){
			$.get(<?php echo '"' . $vcode_url . '"'?> + '?'+ Math.random(),
				function(data){
					$("#vcode").html(data);
					$("#vcode img").attr('title','看不清楚？点击图片切换验证码');
				});
		}

		function checkForm(){
			var username = $("#username").val();
			var password = $("#password").val();
			var email = $("#email").val();
			var password_r = $("#password_r").val();
			var captcha = $("#captcha").val();

			if(username == "" || password == "" || email == "" || password_r == "" || captcha == ""){
				alert("必要信息未填写完成");
				return false;
			}
			if(username.length<3){
				alert("帐号长度至少为3位");
				return false;
			}
			var p = /^([a-zA-Z0-9_]+[_|\_|\.]?)*[a-zA-Z0-9_]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9_]+\.[a-zA-Z]{2,3}$/;
			if (!p.test(email)) {
				alert("Email格式不对");
				return false;
			}
			if(password.length<6){
				alert("密码长度至少为6位");
				return false;
			}
			if (password !== password_r) {
				alert("两次密码输入不一致");
				return false;
			}
		}
		$("#reg_submit").click(checkForm);
	});
</script>
<?php $this->load->view('footer')?>