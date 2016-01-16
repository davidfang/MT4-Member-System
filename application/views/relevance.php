<?php $this->load->view('login_header')?>
<title><?php echo $title ?></title>
<style type="text/css">.popover{width:400px;}</style>
</head>
<body>
	<div class="container">
		<div class="form-signin">
			<?php 
			$attr = array('method'=>'post','class'=>'form-horizontal');
			echo form_open('user/relevance',$attr);
			?>
			<div id="reg_rele">
				<div id="head_info">
					<span class="label label-info">登陆成功 - 关联本站注册帐号</span>
					<div class="alert alert-info" style="margin-top:20px">
						<p>您是否已在本站注册过会员（非MT4帐号）？</p>
						满足以下情况，表示已注册：
						<br />1.在本系统<a href="<?php echo site_url('user/reg');?>" target="_blank" style="color:red">注册页面</a>自行注册过。
						<br />2.已经用自己的Email在此关联过一次。注意：该情况下登录密码默认为为您第一次关联时所用MT4账户的密码。
						<p>若已在本站注册会员，请选择已注册，在下一步页面中填写自行注册或关联时所用电子邮箱（Email）及登陆密码；若未注册会员，请选择未注册，在下一步页面中填写您常用的电子邮箱（Email）即可。</p>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<input type="radio" value="yes" name="is_reg" />已注册
						<input type="radio" value="no" name="is_reg" checked="checked" />未注册
						<button class="btn" id="next_relevance" style="float:right">下一步</button>
					</div>
				</div>
			</div>
			<div id="email_rele" style="display:none">
				<div id="head_info">
					<span class="label label-info">登陆成功 - 关联本站注册帐号</span>
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
				<div class="control-group" id="pwd_rel">
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
						<button class="btn" id="before_relevance">上一步</button>
						<?php 
						$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'确定','class'=>'btn btn-primary','style'=>'margin-left:87px');
						echo form_submit($data);
						?>
					</div>
				</div>
			</div>
			<?php echo form_close()?>
			<script type="text/javascript">

			function checkForm(){
				var password = $("#password").val();
				var email = $("#email").val();
				var is_reg = $("input[name=is_reg][type=radio]:checked").val();
				if(is_reg ==="no"){
					if (email=="") {
						alert("邮箱不能为空");
						return false;
					};
				}else{
					if(password == "" || email == ""){
						alert("邮箱/密码不能为空");
						return false;
					}
					if(password.length<6){
						alert("密码长度至少为6位");
						return false;
					}
				}
				var p = /^([a-zA-Z0-9_]+[_|\_|\.]?)*[a-zA-Z0-9_]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9_]+\.[a-zA-Z]{2,3}$/;
				if (!p.test(email)) {
					alert("Email格式不对");
					return false;
				}
				return true;
			}

			$("#login_submit").click(checkForm);

			$("#next_relevance").click(function(){
				$("#reg_rele").css('display','none');
				var is_reg = $("input[name=is_reg][type=radio]:checked").val();
				if (is_reg==="no") {
					$("#pwd_rel").css('display','none')
				}else{
					$("#pwd_rel").css('display','block')
				};
				$("#email_rele").css('display','block');
				return false;
			});

			$("#before_relevance").click(function(){
				$("#reg_rele").css('display','block');
				$("#email_rele").css('display','none');
				return false;
			});
			</script>
		</div>
		<?php $this->load->view('footer')?>