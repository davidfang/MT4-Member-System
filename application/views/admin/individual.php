<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/individual',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="ds_time">入金时间</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'ds_time',
			'id'=>'ds_time',
			'maxlength'=>'90',
			'style'=>'width:50px',
			'value'=>$deposit_time['start']);
		echo form_input($data);
		?>时
		~
		<?php 
		$data = array('name'=>'de_time','id'=>'de_time','maxlength'=>'90','value'=>$deposit_time['end'],'style'=>'width:50px');
		echo form_input($data);
		?>时
		<span><font color="red">24小时制，精确到小时</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="ds_time_exp">入金时间白名单</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'ds_time_exp','id'=>'ds_time_exp','value'=>implode(',',$deposit_time_exception));
		echo form_input($data);
		?>
		<span>多个MT4账号请用<font color="red">半角逗号</font>分隔，例如201,202,203</span>
	</div>
</div>
<hr>
<div class="control-group">
	<label class="control-label" for="ws_time">出金时间</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'ws_time','id'=>'ws_time','maxlength'=>'90','value'=>$withdraw_time['start'],'style'=>'width:50px');
		echo form_input($data);
		?>时
		~
		<?php 
		$data = array('name'=>'we_time','id'=>'we_time','maxlength'=>'90','value'=>$withdraw_time['end'],'style'=>'width:50px');
		echo form_input($data);
		?>时
		<span><font color="red">24小时制，精确到小时</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="code">出金时间白名单</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'we_time_exp','id'=>'we_time_exp','value'=>implode(',',$withdraw_time_exception));
		echo form_input($data);
		?>
		<span>多个MT4账号请用<font color="red">半角逗号</font>分隔，例如201,202,203</span>
	</div>
</div>
<hr>
<div class="control-group">
	<label class="control-label" for="wmast">单笔最大出金</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'wmast','id'=>'wmast','maxlength'=>'90','value'=>$withdraw_max_amount_single_transaction);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="wmapd">每日最大出金</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'wmapd','id'=>'wmapd','maxlength'=>'90','value'=>$withdraw_max_amount_per_day);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="code">出金额度白名单</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'code','id'=>'code','maxlength'=>'180','value'=>implode(',',$withdraw_max_amount_exception));
		echo form_input($data);
		?>
		<span>多个MT4账号请用<font color="red">半角逗号</font>分隔，例如201,202,203</span>
	</div>
</div>
<hr>
<div class="control-group">
	<label class="control-label" for="mail">禁止出金黑名单</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'mail','id'=>'mail','maxlength'=>'180','value'=>implode(',',$withdraw_blacklist));
		echo form_input($data);
		?>
		<span>多个MT4账号请用<font color="red">半角逗号</font>分隔，例如201,202,203</span>
	</div>
</div>
<hr>
<div class="control-group">
	<label class="control-label" for="max_times">每日最多允许出金次数</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'max_times','id'=>'max_times','maxlength'=>'180','value'=>$withdraw_max_times_per_day);
		echo form_input($data);
		?>
		<span>不限制请填写0</span>
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
			var ds_time = $.trim($("#ds_time").val());
			var de_time = $.trim($("#de_time").val());
			var ds_time_exp = $.trim($("#ds_time_exp").val());

			var ws_time = $.trim($("#ws_time").val());
			var we_time = $.trim($("#we_time").val());
			var we_time_exp = $("#we_time_exp").val();

			var wmast = $.trim($("#wmast").val());
			var wmapd = $.trim($("#wmapd").val());
			var code = $.trim($("#code").val());

			var mail = $.trim($("#mail").val());

			if(ds_time == "" || de_time == "" || ws_time == "" || we_time == "" || wmast == "" || wmapd == ""){
				alert("数据未填写完整");
				return false;
			}
			if ((de_time-ds_time<0) || (we_time-ws_time<0)) {
				alert('结束时间不能小于开始时间');
				return false;
			}

			var t = /^\d$|^[0-1]\d$|^[2][0-4]$/;
			if (!t.test(ds_time) || !t.test(de_time) || !t.test(ws_time) || !t.test(we_time)) {
				alert('时间值有误，应该为0到24的整数值');
				return false;
			}

			if (mail.length != 0 && !test_acc(mail)) {
				return false;
			}
			if (code.length != 0 && !test_acc(code)) {
				return false;
			}
			if (ds_time_exp.length != 0 && !test_acc(ds_time_exp)) {
				return false;
			}
			if (we_time_exp.length != 0 && !test_acc(we_time_exp)) {
				return false;
			}

			var p =/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
			if(!p.test(wmast) || !p.test(wmapd)){
				alert("金额必须为数值，最多精确到小数点后两位");
				return false;
			}
			
		}

		function test_acc(acc){
			var p2 = /^\d+$/;
			var email_array = acc.split(",");
			var len = email_array.length;
			for(var i=0;i<len;i++){
				if(!p2.test(email_array[i])){
					alert("账号非法，账号只能为纯数字");
					return false;
				}
			}
			return true;
		}

		$("#login_submit").click(checkForm);
</script>
<span class="label label-warning">温馨提示</span>
<div class="alert alert-info" style="margin-top:20px">
1.白名单是指不受对应限制的账户；<br />
2.黑名单是指受限制的账户；<br />
3.出入金时间指在设置的每日时间段内可进行出入金，反之其他时间不能出入金。
</div>
</div>

</div>

<?php $this->load->view('admin/footer')?>