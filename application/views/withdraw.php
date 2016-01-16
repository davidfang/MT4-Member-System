<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('user/setinfo/withdraw',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="username">收款人姓名</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'username','id'=>'username','value'=>isset($info['name']) ? $info['name'] : '', 'maxlength'=>'25');
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
	$(function(){
		function checkForm(){
			var username = $.trim($("#username").val());
			var bank = $.trim($("#bank").val());
			var bank_name = $("#bank_name").val();
			if(username == "" || bank == "" || $("#selCountries").val() == 0 || $("#selProvinces").val() == 0 || $("#selCity").val() == 0 || bank_name==""){
				alert("信息填写不完整");
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
				alert("长度不符，请确认收款账户开户行名称是否按规定格式填写");
				return false;
			}
		}
		$("#login_submit").click(checkForm);

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