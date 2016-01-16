<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$withdraw_url = site_url('user/balance');
$loading_url = base_url() . '/public/img/loading.gif';
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('user/transfer/add',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>

<div class="control-group">
	<label class="control-label" for="account">转出MT4帐号</label>
	<div class="controls">
		<select id="account" name="account">
								<option value="">请选择MT4帐号</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['mt4_account']?></option>
								<?php }?>
		</select>
		<span class="help-inline"><font color="red">*</font></span>
		<span class="margin">账户余额获取中。。。<img src="<?php echo $loading_url?>" /></span>
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
	<label class="control-label" for="account_to">转入MT4帐号</label>
	<div class="controls">
		<select id="account_to" name="account_to">
								<option value="">请选择MT4帐号</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>" <?php echo !$v['is_chief'] ? 'selected' : ''?>><?php echo $v['mt4_account']?></option>
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

<input type="hidden" name="free_margin" id="free_margin" value="0" />
<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'提交','class'=>'btn');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
	$(function(){
		$.get(
		<?php echo '"' . $withdraw_url . '/"'?> + $("#account").val() + "/" + $("#server").val(),
		function(data,textStatus){
			changemargin(data);
		},
		"html"
		).fail(function(){
			alert("ajax error");
		});

		function changemargin(data){
			$(".margin").remove();
			if (data == "error") {
				$("#account").parent().append('<span class="margin">0</span>');
				$("#free_margin").val(0);
			}else{
				var value = data.split("|");
				$("#account").parent().append('<span class="margin">余额：' + value[2] +
					' | 净值：' + value[0] + " | 可用保证金：" + value[1] +  " | 信用：" + value[3] + "</span>");
				$("#free_margin").val(value[1] - value[3]);
			}
		}

		function checkForm(){

			var server=$("#server").val();
			var amount = $("#amount").val();
			var free_margin = $("#free_margin").val();
			var account_to = $("#account_to").val();
			var account = $("#account").val();

			if(account == "" || amount == "" || server=="" || account_to == ""){
				alert("信息填写不完整");
				return false;
			}

			if (account == account_to) {
				alert("转出与转入MT4帐号不能相同");
				return false;
			};

			var pm =/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
			if(!pm.test(amount)){
				alert("金额必须为正数值，最多精确到小数点后两位");
				return false;
			}
			
			if(amount==0 || (amount - free_margin > 0)){
				alert("金额不能为0/金额不能大于最大转账额度：" + free_margin);
				return false;
			}
			
			$("#login_submit").attr("value","正在处理中，请稍后。。。");
			$("#login_submit").attr("disabled",true);
			$(".form-horizontal").submit();
			return true;
		}
		$("#login_submit").click(checkForm);

		//获取账户余额
		$("#server,#account").change(function(){
			if ($(this).val() != "") {
				$(".margin").remove();
				$("#login_submit").attr("disabled",true);
				$("#account").parent().append('<span class="margin">账户余额获取中。。。<img src="<?php echo $loading_url?>" /></span>');
				$.get(
					<?php echo '"' . $withdraw_url . '/"'?> + $("#account").val() + "/" + $("#server").val(),
					function(data,textStatus){
						changemargin(data);
						$("#login_submit").attr("disabled",false);
					},
					"html"
					).fail(function(){
						$("#login_submit").attr("disabled",false);
						$("#free_margin").val(0);
						alert("ajax error");
					});
			};
		});
		});
</script>
</div>

</div>
<?php $this->load->view('footer')?>