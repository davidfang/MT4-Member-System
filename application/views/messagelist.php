<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<table class="table table-condensed table-hover">
	<thead>

	</thead>
<?php
if (! empty ( $list )) {
	foreach ( $list as $v ) {
?>
		<td><img src="<?php echo base_url() ?>/public/img/message.gif" style="margin-right: 5px"><a href="<?php echo site_url('user/message') . '/' . $v['id'] ?>"><?php echo $v['title'] ?></a></td>
		<td width="180px">发布于<?php echo $v['addTime'] ?></td>
	</tr>
<?php
}}
?>
    </table>
    <?php echo $pageStr;?>
</div>

</div>

<?php $this->load->view('footer')?>