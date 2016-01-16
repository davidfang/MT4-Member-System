<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal','target'=>'_blank');
echo form_open('huichao/submit',$attr);
?>
<div class ="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="username">MT4帐号</label>
	<div class="controls">
		<select id="username" name="username">
								<option value="">请选择MT4帐号</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['mt4_account']?></option>
								<?php }?>
		</select>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="amount">金额</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'amount','id'=>'amount','maxlength'=>'20');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font>金额必须为数值，最多精确到小数点后两位</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="amount">银行</label>
	<div class="controls">
		<select id="bank" name="bank">
			<option value="">请选择银行</option>
			<option value="ICBC">工商银行</option>
			<option value="ABC">农业银行</option>
			<option value="CCB">建设银行</option>
			<option value="CMB">招商银行</option>
			<option value="CMBC">民生银行</option>
			<option value="ECITIC">中信银行</option>
			<option value="HXB">华夏银行</option>
			<option value="BCOM">交通银行</option>
			<option value="CIB">兴业银行</option>
			<option value="CEBB">光大银行</option>
			<option value="BOC">中国银行</option>
			<option value="SPABANK">平安银行</option>
			<option value="BEA">东亚银行</option>
			<option value="SDB">深圳发展银行</option>
			<option value="GDB">广发银行</option>
			<option value="SPDB">浦发银行</option> 
			<option value="PSBC">中国邮政</option>
			<option value="ZYC">智游卡</option>
			<option value="CMPAY">手机支付</option>
		</select>
	<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="server">服务器</label>
	<div class="controls">
		<select id="server" name="server">
								<option value="">请选择服务器</option>
								<?php foreach ($server as $v){?>
								<option value="<?php echo $v['id']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
								<?php }?>
		</select>
		<span class="help-inline"><font color="red">*</font>选择MT4帐号所在服务器</span>
	</div>
</div>
<div class="control-group" style="display:none">
	<label class="control-label" for="amount">备注</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'remark','id'=>'remark','rows'=>'5');
		echo form_textarea($data);
		?>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'提交','class'=>'btn');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
<?php $this->load->view('deposit_tips') ?>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">提示信息</h3>
</div>
<div class="modal-body">
    <p>请在新窗口中完成本次支付，然后再点击“确定”按钮</p>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">确定</button>
</div>
</div>
<script type="text/javascript">
	$(function(){
		function checkForm(){
			var username = $("#username").val();
			var amount = $("#amount").val();
			var bank = $("#bank").val();
			var server = $("#server").val();

			if(username == "" || amount == "" || bank == "" || server == ""){
				alert("必要信息未填写");
				return false;
			}
			var p =/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
			if(!p.test(amount)){
				alert("金额必须为数值，最多精确到小数点后两位");
				return false;
			}
			if(amount==0){
				alert("金额不能为0");
				return false;
			}

			$('#myModal').modal('show');
			return true;
		}
		$("#login_submit").click(checkForm);

		$('#myModal').on('hidden', function () {
			  window.location.href=window.location.href;
			})
		});
</script>
</div>

</div>
<?php $this->load->view('footer')?>