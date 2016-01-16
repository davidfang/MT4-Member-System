<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<!-- 取款记录start -->
<script type="text/javascript">
$(function() {
        $('#datetimepicker1').datetimepicker({
            language: 'zh-CN'
        });

        $('#datetimepicker2').datetimepicker({
            language: 'zh-CN'
        });

    });
</script>
	<form class="form-inline" id="form_search" method="get">
		<label>MT4帐号：</label>
		<select id="account" name="account">
								<option value="">所有</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>"><?php echo $v['mt4_account']?></option>
								<?php }?>
		</select>
		<label>收款人姓名：</label>
		<input type="text" id="ch_name" name="ch_name" class="input-small"
			placeholder="收款人姓名">
		<label class="text">
			时间范围：
			<div id="datetimepicker1" class="input-append date">
				<input data-format="yyyy-MM-dd hh:mm:ss" type="text"
					placeholder="起始时间" name="start_time" id="start_time"></input>
				<span class="add-on">
					<i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i>
				</span>
			</div>
			至
			<div id="datetimepicker2" class="input-append date">
				<input data-format="yyyy-MM-dd hh:mm:ss" type="text"
					placeholder="结束时间" name="end_time" id="end_time"></input>
				<span class="add-on">
					<i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i>
				</span>
			</div>
		</label>
		<button type="submit" class="btn" id="search">搜索</button>

	</form>
	<div id="result" class="alert alert-success" style="display: none"></div>
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>序号</th>
				<th>MT4帐号</th>
				<th>金额</th>
				<th>收款人姓名</th>
				<th>收款人银行帐号</th>
				<th>区域</th>
				<th>收款账户开户行名称</th>
				<th>时间</th>
				<th>是否下账成功</th>
				<th>汇率</th>
				<th>汇款金额（￥）</th>
			</tr>
		</thead>
            <?php
												if (! empty ( $list )) {
													foreach ( $list as $v ) {
														?>
                    <tr
			<?php echo $v['is_success'] == 0 ? 'class="error"' : 'class="success"' ?>>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['account'] ?></td>
			<td><?php echo $v['amount'] ?></td>
			<td><?php echo $v['name'] ?></td>
			<td><?php echo $v['bank_code'] ?></td>
			<td><?php echo $v['area'] ?></td>
			<td><?php echo $v['bank_name'] ?></td>
			<td><?php echo $v['time'] ?></td>
			<td><?php 
			if($v['is_success'] == 1){
				echo '成功';
			}elseif ($v['is_success'] == 0) {
				echo '失败';
			}else{
				echo '未启用';
			}
			?></td>
			<td><?php echo $v['cjhl'] ?></td>
			<td><?php echo $v['rmb'] ?></td>
		</tr>
                <?php
													
}
												}
												?>
        </table>
<?php
echo $pageStr;
?>
<!-- 入金记录end -->
</div>
</div>
<?php $this->load->view('footer')?>