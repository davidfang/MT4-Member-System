<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open_multipart('user/upload',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
<font color="red">申请真实账户前需先上传您的证件资料：</font>请上传有效身份证件的正反面清晰副本(彩色复印件/扫描件)：
</div>
<div class="control-group">
	<label class="control-label" for="certificate1">证件正面照</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'certificate1','id'=>'certificate1');
		echo form_upload($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
		<br />
		<?php 
			if(isset($cer->certificate1)){
				echo '<img src="' . base_url() . $cer->certificate1 . '" />';
			}
		?>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="certificate2">证件反面照</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'certificate2','id'=>'certificate2');
		echo form_upload($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
		<br />
		<?php 
			if(isset($cer->certificate2)){
				echo '<img src="' . base_url() . $cer->certificate2 . '" />';
			}
		?>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="certificate3">银行卡正面照</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'certificate3','id'=>'certificate3');
		echo form_upload($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
		<br />
		<?php 
			if(isset($cer->certificate3)){
				echo '<img src="' . base_url() . $cer->certificate3 . '" />';
			}
		?>
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'上传','class'=>'btn');
		echo form_submit($data);
		?>
	</div>
</div>
<span class="label label-info">温馨提示</span>
<div class="alert alert-info" style="margin-top:20px">
<p>上传文件格式只允许为jpg/jpeg/bmp/gif</p>
<p>每个上传文件大小不能大于512Kb</p>
<p>有效证件是指：居民身份证、户口簿、暂住证、士兵证、军官证、驾驶证、社保卡和港、澳、台胞的返乡证、华侨及外国人的护照等</p>
<p>上传证件照必须清晰可辨，否则审核不予通过</p>
</div>
<?php echo form_close()?>
<script type="text/javascript">
$(function(){
	$("#login_submit").click(function(){
		var c1 = $("#certificate1").val();
		var c2 = $("#certificate2").val();
		var c3 = $("#certificate3").val();
		if (c1 == "" || c2 == ""  || c3 == "") {
			alert("请选择上传文件！");
			return false;
		}
		$("#login_submit").attr("value","正在处理中，请稍后。。。");
		return true;
	});
});
</script>
</div>

</div>
<?php $this->load->view('footer')?>