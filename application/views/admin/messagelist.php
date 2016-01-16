<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<table class="table table-bordered table-condensed table-hover">
	<h4>系统公告 -> <a href="<?php echo site_url('admin/message/add') ?>">发布新公告</a></h4>
	<thead>
		<tr>
			<th>序号</th>
			<th>标题</th>
			<th>作者</th>
			<th>发布时间</th>
			<th>最后编辑时间</th>
			<th>操作</th>
		</tr>
	</thead>
<?php
if (! empty ( $list )) {
	foreach ( $list as $v ) {
		if ($v['status'] == 0) {
			echo '<tr>';
		} else {
			echo '<tr class="error">';
		}
?>
		<td><?php echo $v['id'] ?></td>
		<td><a href="<?php echo site_url('admin/message/list') . '/' . $v['id'] ?>"><?php echo $v['title'] ?></a></td>
		<td><?php echo $v['creator'] ?></td>
		<td><?php echo $v['addTime'] ?></td>
		<td><?php echo $v['modifyTime'] ?></td>
		<td><?php if ($v['status'] == 0){
			echo '<a href="' . site_url('admin/message/delete') . '/' . $v['id'] . '">删除</a>';
		} else {
			echo '<a href="' . site_url('admin/message/recover') . '/' . $v['id'] . '">恢复</a>';
		}
		?><a href="<?php echo site_url('admin/message/edit') . '/' . $v['id'] ?>" style="margin-left: 6px">编辑</a></td>
	</tr>
<?php
}}
?>
    </table>
    <?php echo $pageStr;?>
</div>

</div>

<?php $this->load->view('admin/footer')?>