<?php $this->load->view('login_header')?>
<title>管理员登陆</title>
<script type="text/javascript">
	$(function(){
		function checkForm(){
			var username = $("#username").val();
			var pwd = $("#password").val();

			if(username == "" || pwd == ""){
				alert("帐号/密码不能为空");
				return false;
			}
			if(username.length<3 || username.length>10){
				alert("帐号长度在3-10个字符");
				return false;
			}
			if (pwd.length < 6) {
				alert("密码长度至少为6位");
				return false;
			};
		}
		$("#login_submit").click(checkForm);
		});
</script>
</head>
<body>
<div class="container">
<div class="form-signin">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/login',$attr);
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

<div class="control-group">
	<label class="control-label" for="username">帐号</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'username','id'=>'username','maxlength'=>'15','placeholder'=>'用户名/Email');
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
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'登陆','class'=>'btn btn-primary');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
</div>
<?php $this->load->view('admin/footer')?>