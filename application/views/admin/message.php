<?php $this->load->view('admin/header')?>
<!--编辑器-->
<script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>public/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>public/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>public/ueditor/lang/zh-cn/zh-cn.js"></script>
    <style type="text/css">
        .clear {
            clear: both;
        }
    </style>
<!--编辑器end-->
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/message/add',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<label for="title"><span class="label label-important">标题</span></label>
		<?php 
		$data = array('name'=>'title','id'=>'title','maxlength'=>'255','style'=>'width:350px');
		echo form_input($data);
		?>

	<label for="content"><span class="label label-important">内容</span></label>

		<script id="editor" type="text/plain" style="width:1024px;height:500px;"></script>
		<input type="hidden" name="content" id="content" value="" />
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'发布','class'=>'btn btn-primary','style'=>'margin:10px 200px');
		echo form_submit($data);
		?>
	</div>
<?php echo form_close()?>
<script type="text/javascript">
	var ue = UE.getEditor('editor');
		function checkForm(){
			$("#content").val(ue.getContent());
			var title = $("#title").val();
			var content = $("#content").val();

			if (title.length == 0 || content.length == 0) {
				alert("标题和内容不能为空");
				return false;
			}
		}
		$("#login_submit").click(checkForm);
</script>
</div>

</div>

<?php $this->load->view('admin/footer')?>