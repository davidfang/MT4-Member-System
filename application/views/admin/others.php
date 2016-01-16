<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/setinfo/others',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="smtp">SMTP服务器</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'smtp','id'=>'smtp','maxlength'=>'90','value'=>$email['host']);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="username">用户名</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'username','id'=>'username','maxlength'=>'90','value'=>$email['username']);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password">密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password','id'=>'password','maxlength'=>'90','value'=>$email['password']);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="code">页面编码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'code','id'=>'code','maxlength'=>'180','value'=>$email['charset']);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="mail">收件人列表</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'mail','id'=>'mail','maxlength'=>'180','value'=>implode(',',$list));
		echo form_input($data);
		?>
		<span>多个收件人请用半角逗号分隔，例如test1@sina.com,test2@sina.com,test3@sina.com</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="notify">是否开启邮件通知</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'notify','id'=>'notify','checked'=>TRUE,'value'=>'1');
		if(!$is_open){
			$data['checked']=FALSE;
		}
		echo form_checkbox($data);;
		?>
	</div>
</div>
<hr />
<div class="control-group">
	<label class="control-label" for="cj">是否开启自动出金</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'cj','id'=>'cj','checked'=>TRUE,'value'=>'1');
		if(!$is_cj){
			$data['checked']=FALSE;
		}
		echo form_checkbox($data);;
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="rj">是否开启自动入金</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'rj','id'=>'rj','checked'=>TRUE,'value'=>'1');
		if(!$is_rj){
			$data['checked']=FALSE;
		}
		echo form_checkbox($data);;
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="zz">是否启用内部转账模块</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'zz','id'=>'zz','checked'=>TRUE,'value'=>'1');
		if(!$is_zz){
			$data['checked']=FALSE;
		}
		echo form_checkbox($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="min_amount" data-toggle="tooltip" title="分别填写单笔入金的最小值和最大值，在此范围外的入金值将被拒绝">入金额度设置（RMB）</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'min_amount','id'=>'min_amount','maxlength'=>'15',
			'value'=>$min_amount,'style'=>'width:100px');
		echo form_input($data);
		?>
		~
		<?php 
		$data = array('name'=>'max_amount','id'=>'max_amount','maxlength'=>'15',
			'value'=>$max_amount,'style'=>'width:100px');
		echo form_input($data);
		?>
		<span></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="rmb_account">人民币帐户识别</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'rmb_account','id'=>'rmb_account','maxlength'=>'180','value'=>$rmb_account);
		echo form_input($data);
		?>
		<span>请填写识别人民币帐户的正则表达式，无多币种账户留空即可</span>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'保存','class'=>'btn');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
		function checkForm(){
			var smtp = $("#smtp").val();
			var username = $("#username").val();
			var pwd = $("#password").val();
			var code = $("#code").val();
			var mail = $("#mail").val();
			var min_amount = $("#min_amount").val();
			var max_amount = $("#max_amount").val();

			if(smtp == "" || username == "" || pwd == "" || code == "" || mail == "" || min_amount == "" || max_amount == ""){
				alert("数据未填写完整");
				return false;
			}

			var p2 = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
			var email_array = mail.split(",");
			var len = email_array.length;
			for(var i=0;i<len;i++){
				if(!p2.test(email_array[i])){
					alert("收件人邮件列表格式非法");
					return false;
				}
			}
		}
		$("#login_submit").click(checkForm);
</script>
</div>

</div>

<?php $this->load->view('admin/footer')?>