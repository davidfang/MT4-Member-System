<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
$hidden = array('id'=>$list['id'],'is_chief'=>$list['is_chief']);
echo form_open('admin/edit/server/save',$attr,$hidden);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="server_name">名称</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'server_name','id'=>'server_name','maxlength'=>'90','value'=>$list['name']);
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="ip">IP地址</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'ip','id'=>'ip','maxlength'=>'90','value'=>$list['ip']);
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="port">端口号</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'port','id'=>'port','maxlength'=>'90','value'=>$list['port']);
		echo form_input($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="WebTraderPort">WebTrader</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'WebTraderPort','id'=>'WebTraderPort','maxlength'=>'90','value'=>$list['params']);
		echo form_input($data);
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="local_port">本地端口号</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'local_port','id'=>'local_port','maxlength'=>'90','value'=>$list['local_port'],'readonly'=>'');
		echo form_input($data);
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
		function checkForm(){
			var server_name = $("#server_name").val();
			var ip = $("#ip").val();
			var port = $("#port").val();
			var local_port = $("#local_port").val();

			if(server_name == "" || ip == "" || port == "" || local_port==""){
				alert("数据未填写完整");
				return false;
			}
			
			var p = /^[0-9]{1,5}$/;
			if(p.test(port)){
				if(port<0 || port >65535 || local_port<0 || local_port >65535){
					alert("端口号应在0-65535之间");
					return false;
				}
			}else{
				alert("端口号应为正整数值");
				return false;
			}
			
			var p2 = /^(([01]?[\d]{1,2})|(2[0-4][\d])|(25[0-5]))(\.(([01]?[\d]{1,2})|(2[0-4][\d])|(25[0-5]))){3}$/;
			if(!p2.test(ip)){
				alert("ip地址非法");
				return false;
			}
		}
		$("#login_submit").click(checkForm);
</script>
</div>

</div>

<?php $this->load->view('admin/footer')?>