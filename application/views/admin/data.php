<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/data/save',$attr);
?>

<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'备份','class'=>'btn btn-primary');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">

</script>
</div>

</div>

<?php $this->load->view('admin/footer')?>