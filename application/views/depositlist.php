<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">

<!-- 入金记录start -->
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
<!-- 
	<div class="mt4_title">
		<h3>在线入金记录-累计金额￥<?php echo $sumDeposition;?></h3>
	</div> -->
	<form class="form-inline" id="form_search" method="get">
		<label>MT4帐号：</label>
		<select id="account" name="account">
								<option value="">所有</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>"><?php echo $v['mt4_account']?></option>
								<?php }?>
		</select>
		<label>交易号：</label>
		<input type="text" id="order_id" name="order_id" class="input-small"
			placeholder="三方支付交易号">
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
				<th>交易号</th>
				<th>订单号</th>
				<th>时间</th>
				<th>是否支付成功</th>
				<th>余额更新是否成功</th>
			</tr>
		</thead>
            <?php
												if (! empty ( $list )) {
													foreach ( $list as $v ) {
														?>
                    <tr
			<?php 
			if ($v['is_ok'] == 0) {
				echo 'class="error"';
			}elseif ($v['is_success'] == 0) {
				echo 'class="warning"';
			}elseif($v['is_success'] != 0 || $v['is_ok']==1){
				echo 'class="success"';
			}
			?>
			>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['account'] ?></td>
			<td><?php echo $v['amount'] ?></td>
			<td><?php echo $v['order_id'] ?></td>
			<td><?php echo $v['my_id'] ?></td>
			<td><?php echo $v['time'] ?></td>
			<td><?php echo $v['is_ok']==0 ? '失败' : '成功' ?></td>
			<td><?php 
			if($v['is_success']==1){
				echo '成功';
			}elseif ($v['is_success']==0) {
				echo '失败';
			}elseif ($v['is_success']==2) {
				echo '未启用即时入金';
			}
			?></td>
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