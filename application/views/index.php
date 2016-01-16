<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('user/setinfo/pwd',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="password_old">旧密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password_old','id'=>'password_old','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password_new">新密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password_new','id'=>'password_new','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font>密码长度至少6位</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password_new_p">确认新密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password_new_p','id'=>'password_new_p','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font>请再次填写新密码</span>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'修改','class'=>'btn');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
	$(function(){
		function checkForm(){
			var pwd_o = $("#password_old").val();
			var pwd_n = $("#password_new").val();
			var pwd_n_p = $("#password_new_p").val();

			if(pwd_o == "" || pwd_n == "" || pwd_n_p == ""){
				alert("密码不能为空");
				return false;
			}
			if(pwd_o.length<6 || pwd_n.length<6){
				alert("密码长度至少为6位");
				return false;
			}
			if (pwd_n !== pwd_n_p) {
				alert("新密码两次输入不一致");
				return false;
			}
			if (pwd_n === pwd_o) {
				alert("新密码与旧密码相同，无需修改");
				return false;
			}
		}
		$("#login_submit").click(checkForm);
		});
</script>
<span class="label label-important">温馨提示</span>
<div style="margin-top: 10px">
<h5>请首先完成以下两步操作</h5>
<div style="margin-top: 10px"><span class="badge badge-info">1</span>
<span style="margin-left: 10px"><a target="_blank" href="<?php echo site_url('user/mt4')?>">绑定MT4帐号</a></span>
<font color="red">没有帐号？</font>
<a target="_blank" href="<?php echo site_url('/user/demo') ?>">申请模拟帐户</a><span class="rate">|</span>
<a target="_blank" href="<?php echo site_url('/user/real') ?>">申请真实帐户</a></div>
<div style="margin-top: 10px"><span class="badge badge-info">2</span><span style="margin-left: 10px">
<a target="_blank" href="<?php echo site_url('user/setinfo/withdraw')?>">设置取款信息</a></span></div>
</div>
<div class="alert alert-success" style="margin-top: 47px;">今日出入金美元兑人民币汇率：买入(<?php echo $deposit_rate; ?>)卖出(<?php echo $withdraw_rate; ?>)<span style="margin:0 15px 0 15px;">-</span>数据来自<a href="http://www.boc.cn/sourcedb/whpj/" target="_blank">中国银行</a></div>
<?php 
if (isset($latestMessage)) {
	echo '<div class="alert alert-info" style="">' . 
	'<img src="' . base_url() . '/public/img/message.gif" style="margin-right: 5px">最新公告：<a href="' . 
	site_url('user/message') . '/' . $latestMessage['id'] . '">' . $latestMessage['title'] . '</a></div>';
}
 ?>
</div>

</div>
<?php $this->load->view('footer')?>