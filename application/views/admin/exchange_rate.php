<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/modify/rate',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="withdraw">出金汇率</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'withdraw','id'=>'withdraw','maxlength'=>'90');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font>正数值，精确到小数点后4位</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="deposit">入金汇率</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'deposit','id'=>'deposit','maxlength'=>'90');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font>正数值，精确到小数点后4位</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="withdraw_factor">出金系数</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'withdraw_factor','id'=>'withdraw_factor','maxlength'=>'90');
		echo form_input($data,isset($msg) ? '' : $withdraw_factor);
		?>
		<span class="help-inline"><font color="red">*</font>0<系数<=1。值为：1-手续费百分比</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="deposit_factor">入金系数</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'deposit_factor','id'=>'deposit_factor','maxlength'=>'90');
		echo form_input($data,isset($msg) ? '' : $deposit_factor);
		?>
		<span class="help-inline"><font color="red">*</font>0<系数<=1。值为：1-手续费百分比</span>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<button class="btn btn-primary" id="getRate">自动获取汇率</button>
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'保存','class'=>'btn',"style"=>"margin-left:43px;");
		echo form_submit($data);
		?>
		<span class="help-inline">汇率值取自<a href="http://www.boc.cn/sourcedb/whpj/" target="_blank">中国银行</a>美元现汇买入/卖出价</span>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
	$(function(){
		$("#getRate").click(function(){
			$(this).text("正在获取中").attr("disabled","disabled");
			$.ajax({
  				url:"<?php echo site_url('common/getRate') ?>",
  				type:"post",
  				success:function(data){
  					if (data === "error") {
  						$("#withdraw").text("0");
  						$("#deposit").text("0");
  					} else{
  						var rate = data.split("|");
  						$("#withdraw").attr("value",(rate[0]/100).toFixed(4));
  						$("#deposit").attr("value",(rate[1]/100).toFixed(4));
  					};
  					$("#getRate").text("自动获取汇率").attr("disabled",false);
  				},
  				fail:function(data) {
  					$("#withdraw").attr("value","error");
  					$("#deposit").attr("value","error");
  					$("#getRate").text("自动获取汇率").attr("disabled",false);
  				}
  			});
  			return false;
		});
		function checkForm(){
			var withdraw = $("#withdraw").val();
			var deposit = $("#deposit").val();
			var withdraw_factor = $("#withdraw_factor").val();
			var deposit_factor = $("#deposit_factor").val();

			if(withdraw == "" || deposit == "" || withdraw_factor == "" || deposit_factor == ""){
				alert("汇率值不能为空");
				return false;
			}
			var p = /^[0-9]+\.[0-9]*$|^[1-9]+$/;
			if(!p.test(withdraw) || !p.test(deposit) || !p.test(withdraw_factor) || !p.test(deposit_factor)){
				alert("汇率值/系数必须为正数值");
				return false;
			}
			if (withdraw_factor <= 0 || withdraw_factor >1 || deposit_factor <=0 || deposit_factor > 1) {
				alert("系数值范围为大于0小于等于1");
				return false;
			};
		}
		$("#login_submit").click(checkForm);
		});
</script>
<!-- 当前汇率设置 -->
<span class="label label-info">当前设置</span>
<div class="alert alert-info" style="margin-top:20px">
<?php
if(!isset($msg)){
	echo '出金汇率：' . $withdraw_rate . '<span class="rate">|</span>';
	echo '入金汇率：' . $deposit_rate . '<span class="rate">|</span>';;
	echo '出金系数：' . $withdraw_factor . '<span class="rate">|</span>';
	echo '入金系数：' . $deposit_factor;
}else{
	echo $msg;
}
?>
</div>
</div>

</div>

<?php $this->load->view('admin/footer')?>