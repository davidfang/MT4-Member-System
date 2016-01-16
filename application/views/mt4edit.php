<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('user/mt4/edit',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<input type="hidden" name="id" value="<?php echo $id ?>">
<input type="hidden" name="serverId" value="<?php echo $server_id ?>">
<div class="control-group">
	<label class="control-label" for="mt4Account">MT4账户</label>
	<div class="controls">
		<div id="bank_repeat"></div>
		<?php 
		$data = array('name'=>'mt4Account','id'=>'mt4Account','value'=>$mt4_account,'readonly'=>'');
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="mt4Pass">MT4密码</label>
	<div class="controls">
		<div id="bank_repeat"></div>
		<?php 
		$data = array('name'=>'mt4Pass','id'=>'mt4Pass','value'=>'');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
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
			var mt4Pass = $.trim($("#mt4Pass").val());
			if(mt4Pass == "" || mt4Pass.length < 5){
				alert("密码长度不能小于5");
				return false;
			}
		}
		$("#login_submit").click(checkForm);
		});
</script>
</div>

</div>
<?php $this->load->view('footer')?>