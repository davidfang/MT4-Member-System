<?php $this->load->view('admin/header')?>
<div class="container-fluid">

<!-- 入金记录start -->
<script type="text/javascript">
$(function() {
        $('#datetimepicker1').datetimepicker({
            language: 'zh-CN'
        });

        $('#datetimepicker2').datetimepicker({
            language: 'zh-CN'
        });

        $("#check_all").click(function(){
            $('input[name="checkbox"]').prop("checked",$(this).prop("checked"));
        });
    
	   	$("#delete").click(function(){
	   		var v =$('input[name="checkbox"]:checked');
	   		var pdata = '';
	   		v.each(function(){
	   			pdata += this.value+ '#';
	   		});
	   		if(pdata === ''){
	   			alert("请先选择要删除的记录！");
	   		}else{
	   			if(!confirm("确定要删除？")){
					return false;
				}
	   	       $.post("<?php echo site_url('admin/transfer/del')?>",{id:pdata,tb:"deposition"},
	   			function(data){
	   				var msg ="";
	   				if(data == "1"){
                        msg="删除成功！";
                        //$('input[name="checkbox"]:checked').parent().parent().hide(1000);
                        $('input[name="checkbox"]:checked').parent().parent().remove();
                	}else if(data =="0"){
                		msg="删除失败！";
                	}else{
                        msg="未知错误！";
                    }
                    $("#result").show().html(msg).delay(1000).hide(1000);
                }
                );
           }
       });
        
    });
</script>
	<form class="form-inline" id="form_search" method="get">
		<label>转出帐号：</label>
		<input type="text" class="input-small" id="account_from"
			name="account_from" placeholder="转出帐号">
		<label>转入帐号：</label>
		<input type="text" id="account_to" name="account_to"
			class="input-small" placeholder="转入帐号">
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
		<button type="button" class="btn" id="delete">删除选中记录</button>
	</form>
	<div id="result" class="alert alert-success" style="display: none"></div>
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="check_all" id="check_all" />
				</th>
				<th>序号</th>
				<th>用户ID</th>
				<th>转出帐号</th>
				<th>转入帐号</th>
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
			<td>
				<input type="checkbox" name="checkbox"
					value="<?php echo $v['id'] ?>" />
			</td>
			<td><?php echo $v['id'] ?></td>
			<td><a href="<?php echo site_url('admin/member/edit/'.$v['user_id']) ?>"><?php echo $v['user_id'] ?></a></td>
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

<?php $this->load->view('admin/footer')?>