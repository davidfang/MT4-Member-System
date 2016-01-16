<?php $this->load->view('admin/header')?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span4">
			<table class="table table-hover">
				<caption><span class="label label-success">入金-Top10</span></caption>
				<thead>
					<tr>
						<td>用户名</td>
						<td>总入金</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($d_data as $v) { ?>
					<tr><td><?php echo $v['username'] ?></td><td><?php echo $v['g_amount'] ?></td></tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="span4">
			<table class="table table-hover">
				<caption><span class="label label-success">出金-Top10</span></caption>
				<thead>
					<tr>
						<td>用户名</td>
						<td>总出金</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($w_data as $v) { ?>
					<tr><td><?php echo $v['username'] ?></td><td><?php echo $v['g_amount'] ?></td></tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="span4">
			<table class="table table-hover">
				<caption><span class="label label-success">(入金-出金)-Top10</span></caption>
				<thead>
					<tr>
						<td>用户名</td>
						<td>差额</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($diff_data as $k=>$v) { ?>
					<tr><td><?php echo $k ?></td><td><?php echo $v ?></td></tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row-fluid">
		<div style="float:right">时间区间：
			<select name="time" id="time">
				<option value="a" selected="selected">全部</option>
				<option value="m">最近一个月</option>
				<option value="q">最近一个季度</option>
				<option value="y">最近一年</option>
			</select>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			var val = <?php echo '"' . $this->uri->segment (3) . '"'; ?>;
			$("option[value="+val+"]").attr('selected',true);
			$("#time").change(function(){
				window.location.href = <?php echo '"' . site_url('admin/top') . '/"'; ?> + $(this).val();
			});
		});
	</script>
</div>

<?php $this->load->view('admin/footer')?>