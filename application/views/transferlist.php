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

        $("#search").click(function(){
			var from = $("#account_from").val();
			var to = $("#account_to").val();
			if(from !='' && to != ''){
				if(from == to){
					alert("转入帐号不能与转出帐号相同");
					return false;
				}
			}
			return true;
        });
        
    });
</script>
	<form class="form-inline" id="form_search" method="get">
		<label>转出：</label>
		<select id="account_from" name="account_from">
								<option value="">所有</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>"><?php echo $v['mt4_account']?></option>
								<?php }?>
		</select>
		<label>转入：</label>
		<select id="account_to" name="account_to">
								<option value="">所有</option>
								<?php foreach ($mt4 as $v){?>
								<option value="<?php echo $v['mt4_account']?>"><?php echo $v['mt4_account']?></option>
								<?php }?>
		</select>
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
				<th>转出MT4帐号</th>
				<th>转入MT4帐号</th>
				<th>金额</th>
				<th>时间</th>
				<th>是否成功</th>
			</tr>
		</thead>
            <?php
												if (! empty ( $list )) {
													foreach ( $list as $v ) {
														?>
                    <tr
			<?php echo $v['is_success'] == 0 ? 'class="error"' : 'class="success"' ?>>

			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['transfer_from'] ?></td>
			<td><?php echo $v['transfer_to'] ?></td>
			<td><?php echo $v['amount'] ?></td>
			<td><?php echo $v['transfer_time'] ?></td>
			<td><?php echo $v['is_success'] == 1 ? '成功' : '失败' ?></td>
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