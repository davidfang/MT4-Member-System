<?php $this->load->view('admin/header')?>
<style>
	table img {
		max-width: 500px;
		max-height: 300px;
	}
</style>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/member/update',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="userid">用户ID</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'userid','id'=>'userid','maxlength'=>'90','value'=>$user['id'],'readonly'=>'');
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="username">用户名</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'username','id'=>'username','maxlength'=>'90','value'=>$user['username'],'readonly'=>'');
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="email">注册邮箱</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'email','id'=>'email','maxlength'=>'120','value'=>$user['email'],'readonly'=>'');
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="mt4_account">已绑定MT4账户</label>
	<div class="controls">
		<select name="mt4_account" id="mt4_account" style="width:220px">
		<?php 
		if (!empty($mt4_account)) {
			foreach ($mt4_account as $v) {
				echo "<option value='{$v['mt4_account']}'>{$v['mt4_account']}|{$v['name']}</option>";
			}
		}else{
			echo "<option value='0'>未绑定MT4账户</option>";
		}
		?>
		</select>
		<button class="btn btn-danger" id="remove_mt4">解除绑定</button>
		<button class="btn btn-info" id="add_mt4_rel">新增绑定</button>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password">密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password','id'=>'password','maxlength'=>'120');
		echo form_input($data);
		?>
		<button class="btn btn-danger" id="modify_pass">重置</button>
		<button class="btn btn-info" id="check_pass">校验</button>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="mail">证件信息</label>
	<div class="controls">
		<table>
		<tr>
			<td colspan="2">证件正面照

			<?php 
				if(isset($user['certificate']->certificate1)){
					echo '<img src="' . base_url() . $user['certificate']->certificate1 . '" />';
				}else{
					echo '<font color="red">未上传</font>';
				}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">证件反面照

			<?php 
				if(isset($user['certificate']->certificate2)){
					echo '<img src="' . base_url() . $user['certificate']->certificate2 . '" />';
				}else{
					echo '<font color="red">未上传</font>';
				}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">银行卡正面照

			<?php 
				if(isset($user['certificate']->certificate3)){
					echo '<img src="' . base_url() . $user['certificate']->certificate3 . '" />';
				}else{
					echo '<font color="red">未上传</font>';
				}
			?>
			</td>
		</tr>
		</table>
	</div>
</div>

<?php echo form_close()?>

<div id="add_account_rel" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">新增MT4账户绑定</h4><div id="mt4_acc_result" style="color:red;font-size:18px"></div>
	</div>
	<div class="modal-body">
		
			<?php 
			$attr = array('method'=>'post','id'=>'form_mt4_rel','class'=>'form-horizontal');
			echo form_open('admin/mt4add',$attr);?>
			<input type="hidden" name="user_id" value="<?php echo $user['id'] ?>" />
			<div class="control-group">
			   <label class="control-label" for="mt4_acc" style="color:blue">MT4帐号</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'mt4_acc','id'=>'mt4_acc','value'=>'', 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="mt4_pass" style="color:blue">MT4密码</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'mt4_pass','id'=>'mt4_pass','value'=>'', 'maxlength'=>'25');
			      echo form_password($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="server" style="color:blue">服务器</label>
				<div class="controls">
					<select id="server" name="server">
						<option value="">请选择服务器</option>
						<?php foreach ($server as $v){?>
						<option value="<?php echo $v['id']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
						<?php }?>
					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
				<span class="help-inline" style="color: red">绑定前请仔细确认提交信息是否有误</span>
				</div>
			</div>
			<?php
			echo form_close();
			?>
		</div>
	
	<div class="modal-footer">
		<button class="btn" id="open_account_close" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button class="btn btn-primary" id="do_add_account_rel">绑定</button>
	</div>
</div>

<script type="text/javascript">
		$("#remove_mt4").click(function(e){
			e.preventDefault();
			var mtAccount = $("#mt4_account").val();
			if (!confirm("确定解除绑定当前选定mt4账号："+ mtAccount + " ？")) {
				return false;
			}

			$(this).attr("disabled",true);
			$.post('<?php echo site_url("admin/removeMTAccount"); ?>',{userid:$("#userid").val(),mtacc:mtAccount},
			function(data){
				if (typeof data === 'object') {
					if (data.result === 1) {
						alert('解除绑定成功');
						$("option[value='" + mtAccount + "']").remove();
					}else if (data.result === 0){
						alert('解除绑定失败');
					}else{
						alert('其它错误');
					}
				}
			},'json').fail(function(data){
				alert('请求失败，请稍后再试');
			}).always(function(){
				$("#remove_mt4").attr("disabled",false);
			});
		});
		$("#check_pass").click(function(e){
			e.preventDefault();
			var pass = $("#password").val().trim();
			if (pass.length === 0) {
				alert('请输入密码');
				return false;
			}

			$(this).attr("disabled",true);
			$.post('<?php echo site_url("admin/checkpass"); ?>',{username:$("#username").val(),password:pass},
			function(data){
				if (typeof data === 'object') {
					if (data.result === 1) {
						alert('密码正确');
					}else if (data.result === 0){
						alert('密码错误');
					}else{
						alert('其它错误');
					}
				}
			},'json').fail(function(data){
				alert('查询失败，请稍后再试');
			}).always(function(){
				$("#check_pass").attr("disabled",false);
			});
		});
		$("#modify_pass").click(function(e){
			e.preventDefault();
			if (!confirm("确定重置当前选定用户的密码为 123456 ？")) {
				return false;
			}

			$(this).attr("disabled",true);
			$.post('<?php echo site_url("admin/resetpass"); ?>',{username:$("#username").val()},
			function(data){
				if (typeof data === 'object') {
					if (data.result === 1) {
						alert('密码重置成功');
					}else if (data.result === 0){
						alert('密码重置失败');
					}else{
						alert('其它错误');
					}
				}
			},'json').fail(function(data){
				alert('重置失败，请稍后再试');
			}).always(function(){
				$("#modify_pass").attr("disabled",false);
			});
		});

		$("#add_mt4_rel").click(function(){
			$("#add_account_rel").modal('show');
			return false;
		});

		$("#do_add_account_rel").click(function(){
			var mt4_acc = $.trim($("#mt4_acc").val());
			var mt4_pass = $.trim($("#mt4_pass").val());

			if (mt4_acc.length == 0) {
				alert("MT4帐号不能为空");
				return false;
			}
			var p = /^[0-9]{1,15}$/;
			if(mt4_acc != "" && !p.test(mt4_acc)){
				alert("MT4帐号应为纯数字");
				return false;
			}

			if(mt4_pass.length < 5){
				alert("密码长度不能小于5位");
				return false;
			}

			$(this).text("处理中。。。请勿关闭本窗口").attr("disabled","disabled");

			$.ajax({
  			url:"<?php echo site_url('admin/mt4add') ?>",
  			type:"post",
  			dataType:"json",
  			data:$("#form_mt4_rel").serialize(),
  			success:function(data){
  				$("#mt4_acc_result").empty();
  				if (data != null && typeof(data.success) != 'undefined') {
  					var reason = "";
  					if (data.success == true) {
  						alert("绑定成功！");
  						$("#mt4_acc_result").append("绑定成功！");
  						$("#mt4_account").append('<option value="' + data.account + '">' +
  							data.account + '|' + data.server_name + '</option>');
  					} else {
  						alert("绑定失败。原因：" + data.reason);
  					}
  				} else {
  					alert("未知错误" + data);
  				}
  				$("#do_add_account_rel").text("绑定").attr("disabled",false);
  			},
  			fail:function(data) {
  				alert("请求错误，请重试。" + data);
  				$("#mt4_acc_result").empty();
  				$("#do_add_account_rel").text("绑定").attr("disabled",false);
  			}
  		});
		});

		if ($("#mt4_account").val() == 0) {
			$("#remove_mt4").hide();
		}
</script>
</div>

</div>

<?php $this->load->view('admin/footer')?>