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
echo form_open('user/withdraw/add',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>

<div class="control-group">
	<label class="control-label" for="account">MT4帐号</label>
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
	<label class="control-label" for="username">收款人姓名</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'username','id'=>'username','value'=>isset($info['name']) ? $info['name'] : '', 'maxlength'=>'20');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="bank">收款人银行帐号</label>
	<div class="controls">
		<div id="bank_repeat"></div>
		<?php 
		$data = array('name'=>'bank','id'=>'bank','value'=>isset($info['code']) ? $info['code'] : '','maxlength'=>'20');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font>16-19位数字</span>
	</div>
</div>
<div class="control-group">
<label class="control-label" for="country">国家</label>
<div class="controls">
<select name="country" id="selCountries" >
        <option value="0">请选择国家</option>
        <?php foreach ($country as $v){?>
        <option value="<?php echo $v['region_id'] ?>" selected><?php echo $v['region_name'] ?></option>
        <?php }?>
</select>
<span class="help-inline"><font color="red">*</font></span>
</div>
</div>
<div class="control-group">
<label class="control-label" for="province">省份</label>
<div class="controls">
      <select name="province" id="selProvinces" >
        <option value="0">请选择省</option>
                <?php foreach ($province as $v){?>
        		<option value="<?php echo $v['region_id'] ?>" 
        		<?php 
        		if(isset($info)){
        			if($info['province'] == $v['region_id']){
        				echo 'selected';
        			}
        		}
        		?>
        		><?php echo $v['region_name'] ?></option>
        		<?php }?>
              </select>
              <span class="help-inline"><font color="red">*</font></span>
              </div></div>
<div class="control-group">
	<label class="control-label" for="city">城市</label>
	<div class="controls">
<select name="city" id="selCity" >
        <option value="0">请选择市</option>
        <?php 
		if(isset($info)){
			foreach ($city as $v){?>
		<option value="<?php echo $v['region_id'] ?>" 
		<?php
			if($info['city'] == $v['region_id']){
				echo 'selected';
			}?>>
		<?php echo $v['region_name'] ?></option>
		<?php }}?>
</select>
        		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="bank_name">收款账户开户行名称</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'bank_name','id'=>'bank_name','value'=>isset($info['address']) ? $info['address'] : '','maxlength'=>'40','class'=>'input-xlarge');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font>填写格式：XX银行XX省分行XX市XX支行</span>
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
<span class="label label-info">温馨提示</span>
<div class="alert alert-info" style="margin-top:20px">
<p>出金货币为交易系统所用货币（美元或其它货币类型），提交出金申请并经管理员审核通过后，系统会根据<a href="<?php echo site_url('user/index') ?>" target="_blank">当前汇率</a>自动转换为人民币（RMB）汇入您指定的银行账户内</p>
<p>为保证您的资金快速到账，请确认您的取款信息准确无误</p>
<p>修改或设置取款信息，请点击<a href="<?php echo site_url('user/setinfo/withdraw') ?>" style="color:red">取款信息设置</a></p>
</div>
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
					' | 净值：' + value[0] + " | 可用保证金：" + value[1] + " | 信用：" + value[3] + "</span>");
				$("#free_margin").val(value[1] - value[3]);
			}
		}

		function checkForm(){
			var username = $("#username").val();
			var bank = $("#bank").val();
			var bank_name = $("#bank_name").val();
			var server=$("#server").val();
			var amount = $("#amount").val();
			var free_margin = $("#free_margin").val();

			if(username == "" || bank == "" || $("#account").val() == "" || amount == "" || $("#selCountries").val() == 0 || $("#selProvinces").val() == 0 || $("#selCity").val() == 0 || bank_name=="" || server==""){
				alert("信息填写不完整");
				return false;
			}

			var pm =/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
			if(!pm.test(amount)){
				alert("金额必须为正数值，最多精确到小数点后两位");
				return false;
			}
			if(amount==0 || (amount - free_margin > 0)){
				alert("金额不能为0/金额不能大于最大取款额度：" + free_margin);
				return false;
			}
			
			p=/[0-9]+/;
			if(username.length<2 || p.test(username)){
				alert("姓名非法");
				return false;
			}
			p=/^[0-9]{16,19}$/;
			if(!p.test(bank)){
				alert("收款人银行帐号非法");
				return false;
			}
			if(bank_name.length<10){
				alert("请确认收款账户开户行名称是否按规定格式填写");
				return false;
			}
			$("#login_submit").attr("value","正在处理中，请稍后。。。");
			$("#login_submit").attr("disabled",true);
			$(".form-horizontal").submit();
			return true;
		}
		$("#login_submit").click(checkForm);

		//获取账户余额信息
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

		//国家、省、市联动
		$("#selProvinces").click(function(){
			$.ajax({
				type:"post",
				url:"<?php echo site_url('region/index/' . '2')?>" + "/" + $(this).val(),
				success:function(result){
					$("#selCity").html(result);
				},
				error:function(result){
					
				}
				});
			});

		$("#selCountries").change(function(){
			$.ajax({
				type:"post",
				url:"<?php echo site_url('region/index/' . '1')?>" + "/" + $(this).val(),
				success:function(result){
					$("#selProvinces").html(result);
				},
				error:function(result){
					
				}
				});
			});
		function Xreplace(str,length,reversed,txt)
		{
			var re = new RegExp("\\d{1,"+length+"}","g");
			ma = str.match(re);
			if(reversed) ma.reverse();
			return ma === null ? "" : ma.join(txt);
		}
		$("#bank").keyup(function(e){
			$("#bank_repeat").empty()
				.html(Xreplace($(this).val(),4,false," "));
		}).trigger("keyup");
		});
</script>
</div>

</div>
<?php $this->load->view('footer')?>